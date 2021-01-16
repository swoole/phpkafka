<?php

declare(strict_types=1);

namespace longlang\phpkafka\Consumer;

use InvalidArgumentException;
use longlang\phpkafka\Broker;
use longlang\phpkafka\Client\ClientInterface;
use longlang\phpkafka\Consumer\Assignor\PartitionAssignorInterface;
use longlang\phpkafka\Consumer\Struct\ConsumerGroupMemberMetadata;
use longlang\phpkafka\Exception\KafkaErrorException;
use longlang\phpkafka\Group\CoordinatorType;
use longlang\phpkafka\Group\GroupManager;
use longlang\phpkafka\Group\ProtocolType;
use longlang\phpkafka\Group\Struct\ConsumerGroupMemberAssignment;
use longlang\phpkafka\Protocol\ErrorCode;
use longlang\phpkafka\Protocol\Fetch\FetchableTopic;
use longlang\phpkafka\Protocol\Fetch\FetchPartition;
use longlang\phpkafka\Protocol\Fetch\FetchRequest;
use longlang\phpkafka\Protocol\Fetch\FetchResponse;
use longlang\phpkafka\Protocol\JoinGroup\JoinGroupRequestProtocol;
use longlang\phpkafka\Util\KafkaUtil;
use Swoole\Timer;

class Consumer
{
    /**
     * @var ConsumerConfig
     */
    protected $config;

    /**
     * @var Broker
     */
    protected $broker;

    /**
     * @var callable|null
     */
    protected $consumeCallback;

    /**
     * @var GroupManager
     */
    protected $groupManager;

    /**
     * @var OffsetManager[]
     */
    protected $offsetManagers = [];

    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var string
     */
    protected $memberId;

    /**
     * @var int
     */
    protected $generationId;

    /**
     * @var bool
     */
    private $started = false;

    /**
     * @var ConsumeMessage[]
     */
    private $messages = [];

    /**
     * @var bool
     */
    private $swooleHeartbeat;

    /**
     * @var float
     */
    private $lastHeartbeatTime = 0;

    /**
     * @var int|null
     */
    private $heartbeatTimerId;

    /**
     * @var ConsumerGroupMemberAssignment
     */
    private $consumerGroupMemberAssignment;

    /**
     * @var PartitionAssignorInterface
     */
    private $assignor;

    public function __construct(ConsumerConfig $config, ?callable $consumeCallback = null)
    {
        $this->config = $config;
        $this->consumeCallback = $consumeCallback;

        $this->broker = $broker = new Broker($config);
        if ($config->getUpdateBrokers()) {
            $broker->updateBrokers();
        } else {
            $broker->setBrokers($config->getBroker());
        }

        $this->client = $broker->getClient();
        $this->groupManager = $groupManager = new GroupManager($broker);
        $groupId = $config->getGroupId();

        $this->broker->updateMetadata($config->getTopic());

        // findCoordinator
        $groupManager->findCoordinator($groupId, CoordinatorType::GROUP, $config->getGroupRetry(), $config->getGroupRetrySleep());

        $this->rejoin();
    }

    public function rejoin()
    {
        if ($this->swooleHeartbeat) {
            $this->stopHeartbeat();
        }
        $config = $this->config;
        $groupManager = $this->groupManager;
        $groupId = $config->getGroupId();
        $topics = $config->getTopic();
        $client = $this->broker->getClient();

        $metadata = new ConsumerGroupMemberMetadata();
        $metadata->setTopics($config->getTopic());
        $metadataContent = $metadata->pack();
        $protocolName = 'group';
        $protocols = [
            (new JoinGroupRequestProtocol())->setName($protocolName)->setMetadata($metadataContent),
        ];

        // joinGroup
        $response = $groupManager->joinGroup($groupId, $config->getMemberId(), ProtocolType::CONSUMER, $config->getGroupInstanceId(), $protocols, (int) ($config->getSessionTimeout() * 1000), (int) ($config->getRebalanceTimeout() * 1000), $config->getGroupRetry(), $config->getGroupRetrySleep());
        $this->memberId = $response->getMemberId();
        $this->generationId = $response->getGenerationId();

        // syncGroup
        if ($this->groupManager->isLeader()) {
            $assignorClass = $config->getPartitionAssignmentStrategy();
            /** @var PartitionAssignorInterface $assignor */
            $assignor = $this->assignor = new $assignorClass();
            $assignments = $assignor->assign($this->broker->getTopicsMeta(), $this->groupManager->getJoinGroupResponse()->getMembers());
            $response = $groupManager->syncGroup($groupId, $config->getGroupInstanceId(), $this->memberId, $this->generationId, $protocolName, ProtocolType::CONSUMER, $assignments, $config->getGroupRetry(), $config->getGroupRetrySleep());
        } else {
            $response = $groupManager->syncGroup($groupId, $config->getGroupInstanceId(), $this->memberId, $this->generationId, $protocolName, ProtocolType::CONSUMER, [], $config->getGroupRetry(), $config->getGroupRetrySleep());
        }

        $this->consumerGroupMemberAssignment = $consumerGroupMemberAssignment = new ConsumerGroupMemberAssignment();
        $data = $response->getAssignment();
        if ('' !== $data) {
            $consumerGroupMemberAssignment->unpack($data);
        }

        foreach ($topics as $topic) {
            $this->offsetManagers[$topic] = $offsetManager = new OffsetManager($client, $topic, $this->getPartitions($topic), $groupId, $config->getGroupInstanceId(), $this->memberId, $this->generationId);
            $offsetManager->updateOffsets($config->getOffsetRetry());
        }

        $this->swooleHeartbeat = KafkaUtil::inSwooleCoroutine();
        if ($this->swooleHeartbeat) {
            $this->startHeartbeat();
        }
    }

    public function close()
    {
        $config = $this->config;
        $groupId = $config->getGroupId();
        if (null !== $groupId) {
            $this->groupManager->leaveGroup($groupId, $this->memberId, $config->getGroupInstanceId(), $config->getGroupRetry(), $config->getGroupRetrySleep());
        }
        $this->broker->close();
        $this->stopHeartbeat();
    }

    public function start()
    {
        $consumeCallback = $this->consumeCallback;
        if (null === $consumeCallback) {
            throw new InvalidArgumentException('consumeCallback must not null');
        }
        $interval = (int) ($this->config->getInterval() * 1000000);
        $this->started = true;
        $autoCommit = $this->config->getAutoCommit();
        while ($this->started) {
            $message = $this->consume();
            if (null === $message) {
                if ($interval > 0) {
                    usleep($interval);
                }
            } else {
                $consumeCallback($message);
                if ($autoCommit) {
                    $this->ack($message);
                }
            }
        }
    }

    public function stop()
    {
        $this->started = false;
    }

    public function consume(): ?ConsumeMessage
    {
        if ([] === $this->messages) {
            $this->fetchMessages();
        }
        $message = array_shift($this->messages);

        return $message;
    }

    public function ack(ConsumeMessage $message)
    {
        $offsetManager = $this->getOffsetManager($message->getTopic());
        $partition = $message->getPartition();
        $offsetManager->addFetchOffset($partition);
        $offsetManager->saveOffsets($partition, $this->config->getOffsetRetry());
    }

    protected function fetchMessages()
    {
        if (!$this->swooleHeartbeat) {
            $this->checkBeartbeat();
        }
        $config = $this->config;
        $request = new FetchRequest();
        $request->setReplicaId($config->getReplicaId());
        $recvTimeout = $config->getRecvTimeout();
        if ($recvTimeout < 0) {
            $request->setMaxWait(60000);
        } else {
            $request->setMaxWait((int) ($recvTimeout * 1000));
        }
        $request->setRackId($config->getRackId());
        $topics = [];
        foreach ($config->getTopic() as $topic) {
            $fetchPartitions = [];
            foreach ($this->getFetchPartitions($topic) as $partition) {
                $fetchPartitions[] = (new FetchPartition())->setPartitionIndex($partition)->setFetchOffset($this->getOffsetManager($topic)->getFetchOffset($partition));
            }
            $topics[] = (new FetchableTopic())->setName($topic)->setFetchPartitions($fetchPartitions);
        }
        $request->setTopics($topics);

        /** @var FetchResponse $response */
        $response = $this->client->sendRecv($request);
        $errorCode = $response->getErrorCode();
        switch ($errorCode) {
            case ErrorCode::REBALANCE_IN_PROGRESS:
                $this->rejoin();

                return;
            default:
                ErrorCode::check($errorCode);
        }

        $messages = [];
        foreach ($response->getTopics() as $topic) {
            foreach ($topic->getPartitions() as $partition) {
                ErrorCode::check($partition->getErrorCode());
                foreach ($partition->getRecords()->getRecords() as $record) {
                    $messages[] = new ConsumeMessage($this, $topic->getName(), $partition->getPartitionIndex(), $record->getKey(), $record->getValue(), $record->getHeaders());
                }
            }
        }
        $this->messages = $messages;
    }

    public function getConfig(): ConsumerConfig
    {
        return $this->config;
    }

    public function getBroker(): Broker
    {
        return $this->broker;
    }

    protected function startHeartbeat()
    {
        $this->heartbeatTimerId = Timer::tick((int) ($this->config->getGroupHeartbeat() * 1000), function () {
            $this->heartbeat();
        });
    }

    protected function stopHeartbeat()
    {
        if ($this->heartbeatTimerId) {
            Timer::clear($this->heartbeatTimerId);
            $this->heartbeatTimerId = null;
        }
    }

    protected function heartbeat()
    {
        $config = $this->config;
        try {
            $this->groupManager->heartbeat($config->getGroupId(), $config->getGroupInstanceId(), $this->memberId, $this->generationId);
        } catch (KafkaErrorException $kafkaErrorException) {
            switch ($kafkaErrorException->getCode()) {
                case ErrorCode::REBALANCE_IN_PROGRESS:
                    $this->rejoin();
                    break;
                default:
                    throw $kafkaErrorException;
            }
        }
    }

    protected function checkBeartbeat()
    {
        $time = microtime(true);
        if ($time - $this->lastHeartbeatTime >= $this->config->getGroupHeartbeat()) {
            $this->lastHeartbeatTime = $time;
            $this->heartbeat();
        }
    }

    protected function getPartitions(string $topic): array
    {
        $partitions = [];
        foreach ($this->broker->getTopicsMeta() as $topicMeta) {
            if ($topicMeta->getName() === $topic) {
                foreach ($topicMeta->getPartitions() as $partition) {
                    $partitions[] = $partition->getPartitionIndex();
                }
                break;
            }
        }

        return $partitions;
    }

    protected function getFetchPartitions(string $topic): array
    {
        $partitions = [];
        foreach ($this->consumerGroupMemberAssignment->getTopics() as $consumerGroupMemberAssignmentTopic) {
            if ($consumerGroupMemberAssignmentTopic->getTopicName() === $topic) {
                foreach ($consumerGroupMemberAssignmentTopic->getPartitions() as $partition) {
                    $partitions[] = $partition;
                }
                break;
            }
        }

        return $partitions;
    }

    public function getOffsetManager(string $topic): OffsetManager
    {
        if (!isset($this->offsetManagers[$topic])) {
            throw new \RuntimeException(sprintf('Topic %s does not exists', $topic));
        }

        return $this->offsetManagers[$topic];
    }
}
