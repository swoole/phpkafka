<?php

declare(strict_types=1);

namespace longlang\phpkafka\Consumer;

use InvalidArgumentException;
use longlang\phpkafka\Broker;
use longlang\phpkafka\Client\ClientInterface;
use longlang\phpkafka\Group\GroupManager;
use longlang\phpkafka\Group\ProtocolType;
use longlang\phpkafka\Protocol\ErrorCode;
use longlang\phpkafka\Protocol\Fetch\FetchableTopic;
use longlang\phpkafka\Protocol\Fetch\FetchPartition;
use longlang\phpkafka\Protocol\Fetch\FetchRequest;
use longlang\phpkafka\Protocol\Fetch\FetchResponse;

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
     * @var OffsetManager
     */
    protected $offsetManager;

    /**
     * @var ClientInterface
     */
    protected $client;

    private $started = false;

    /**
     * @var ConsumeMessage[]
     */
    private $messages = [];

    public function __construct(ConsumerConfig $config, ?callable $consumeCallback = null)
    {
        $this->config = $config;
        $this->consumeCallback = $consumeCallback;
        $this->broker = $broker = new Broker($config);
        $broker->setBrokers([$config->getBroker()]);
        $this->client = $client = $broker->getClient();
        $this->groupManager = $groupManager = new GroupManager($client);
        $groupId = $config->getGroupId();
        if (null !== $groupId) {
            $groupManager->joinGroup($groupId, $config->getMemberId(), ProtocolType::CONSUMER, $config->getGroupInstanceId(), $config->getProtocols(), (int) ($config->getSessionTimeout() * 1000), (int) ($config->getRebalanceTimeout() * 1000));
        }
        $this->offsetManager = $offsetManager = new OffsetManager($client, $config->getTopic(), $config->getPartitions(), $config->getGroupId() ?? '', $config->getGroupInstanceId(), $config->getMemberId());
        $offsetManager->updateOffsets();
    }

    public function close()
    {
        $config = $this->config;
        $groupId = $config->getGroupId();
        if (null !== $groupId) {
            $this->groupManager->leaveGroup($groupId, $config->getMemberId(), $config->getGroupInstanceId());
        }
        $this->broker->close();
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
            $result = $this->consume();
            if (null === $result) {
                if ($interval > 0) {
                    usleep($interval);
                }
            } else {
                $consumeCallback($result);
                if ($autoCommit) {
                    $this->ack($result->getPartition());
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

    public function ack(int $partition)
    {
        $this->offsetManager->addFetchOffset($partition);
        $this->offsetManager->saveOffsets($partition);
    }

    protected function fetchMessages()
    {
        $config = $this->config;
        $request = new FetchRequest();
        $request->setReplicaId($config->getReplicaId());
        $recvTimeout = $config->getRecvTimeout();
        if ($recvTimeout < 0) {
            $request->setMaxWait(60000);
        } else {
            $request->setMaxWait((int) ($recvTimeout * 1000));
        }
        $topic = $config->getTopic();
        $request->setRackId($config->getRackId());
        $fetchPartitions = [];
        foreach ($config->getPartitions() as $partition) {
            $fetchPartitions[] = (new FetchPartition())->setPartitionIndex($partition)->setFetchOffset($this->offsetManager->getFetchOffset($partition));
        }
        $request->setTopics([
            (new FetchableTopic())->setName($topic)->setFetchPartitions($fetchPartitions),
        ]);

        /** @var FetchResponse $response */
        $response = $this->client->sendRecv($request);
        ErrorCode::check($response->getErrorCode());

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

    /**
     * @return ConsumerConfig
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return Broker
     */
    public function getBroker()
    {
        return $this->broker;
    }
}
