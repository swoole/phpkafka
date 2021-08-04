<?php

declare(strict_types=1);

namespace longlang\phpkafka\Consumer;

use longlang\phpkafka\Broker;
use longlang\phpkafka\Protocol\ErrorCode;
use longlang\phpkafka\Protocol\ListOffset\ListOffsetPartition;
use longlang\phpkafka\Protocol\ListOffset\ListOffsetRequest;
use longlang\phpkafka\Protocol\ListOffset\ListOffsetResponse;
use longlang\phpkafka\Protocol\ListOffset\ListOffsetTopic;
use longlang\phpkafka\Protocol\OffsetCommit\OffsetCommitRequest;
use longlang\phpkafka\Protocol\OffsetCommit\OffsetCommitRequestPartition;
use longlang\phpkafka\Protocol\OffsetCommit\OffsetCommitRequestTopic;
use longlang\phpkafka\Protocol\OffsetCommit\OffsetCommitResponse;
use longlang\phpkafka\Protocol\OffsetFetch\OffsetFetchRequest;
use longlang\phpkafka\Protocol\OffsetFetch\OffsetFetchRequestTopic;
use longlang\phpkafka\Protocol\OffsetFetch\OffsetFetchResponse;
use longlang\phpkafka\Util\KafkaUtil;

class OffsetManager
{
    /**
     * @var Broker
     */
    protected $broker;

    /**
     * @var string
     */
    protected $topic;

    /**
     * @var int[]
     */
    protected $partitions;

    /**
     * @var string
     */
    protected $groupId;

    /**
     * @var string|null
     */
    protected $groupInstanceId;

    /**
     * @var string
     */
    protected $memberId;

    /**
     * @var int
     */
    protected $generationId;

    /**
     * offsets map.
     *
     * partition => offset
     *
     * @var int[]
     */
    private $offsets;

    /**
     * @var string[]
     */
    private $metadatas;

    /**
     * @var int
     */
    private $coordinatorNodeId;

    public function __construct(Broker $broker, int $coordinatorNodeId, string $topic, array $partitions, string $groupId, ?string $groupInstanceId, string $memberId, int $generationId)
    {
        $this->broker = $broker;
        $this->coordinatorNodeId = $coordinatorNodeId;
        $this->topic = $topic;
        $this->partitions = $partitions;
        $this->groupId = $groupId;
        $this->groupInstanceId = $groupInstanceId;
        $this->memberId = $memberId;
        $this->generationId = $generationId;
    }

    public function updateOffsets(int $retry = 0): void
    {
        $client = $this->broker->getClient($this->coordinatorNodeId);

        $request = new OffsetFetchRequest();
        $request->setGroupId($this->groupId);
        $request->setTopics([
            (new OffsetFetchRequestTopic())->setName($this->topic)->setPartitionIndexes($this->partitions),
        ]);

        /** @var OffsetFetchResponse $response */
        $response = KafkaUtil::retry($client, $request, $retry, 0);

        $metadatas = $offsets = [];
        foreach ($response->getTopics() as $topic) {
            foreach ($topic->getPartitions() as $partition) {
                $partitionIndex = $partition->getPartitionIndex();
                $offsets[$partitionIndex] = max($partition->getCommittedOffset(), 0);
                $metadatas[$partitionIndex] = $partition->getMetadata();
            }
        }
        $this->offsets = $offsets;
        $this->metadatas = $metadatas;
    }

    public function updateListOffsets(array $partitions, int $retry = 0): void
    {
        $brokerPartitionMap = [];
        $broker = $this->broker;
        $topicName = $this->topic;
        foreach ($partitions as $partition) {
            $brokerPartitionMap[$broker->getBrokerIdByTopic($topicName, $partition)][] = $partition;
        }
        $topicsMeta = $broker->getTopicsMeta($topicName);
        foreach ($brokerPartitionMap as $brokerId => $partitions) {
            $request = new ListOffsetRequest();
            $topicPartitions = [];
            foreach ($partitions as $partition) {
                $topicPartitions[] = $listOffsetPartition = new ListOffsetPartition();
                foreach ($topicsMeta as $topicMeta) {
                    if ($topicMeta->getName() === $topicName) {
                        foreach ($topicMeta->getPartitions() as $partitionObject) {
                            if ($partition === $partitionObject->getPartitionIndex()) {
                                $listOffsetPartition->setCurrentLeaderEpoch($partitionObject->getLeaderEpoch());
                                break;
                            }
                        }
                        break;
                    }
                }
                $listOffsetPartition->setPartitionIndex($partition)->setTimestamp(-1);
            }
            $request->setTopics([
                (new ListOffsetTopic())->setName($topicName)->setPartitions($topicPartitions),
            ]);
            $client = $broker->getClientByBrokerId($brokerId);
            /** @var ListOffsetResponse $response */
            $response = KafkaUtil::retry($client, $request, $retry, 0);
            foreach ($response->getTopics() as $topic) {
                foreach ($topic->getPartitions() as $partition) {
                    $this->offsets[$partition->getPartitionIndex()] = $partition->getOffset() - 1;
                }
            }
        }
    }

    public function getBroker(): Broker
    {
        return $this->broker;
    }

    public function getTopic(): string
    {
        return $this->topic;
    }

    /**
     * @return int[]
     */
    public function getPartitions(): array
    {
        return $this->partitions;
    }

    /**
     * @return int[]
     */
    public function getOffsets(): array
    {
        return $this->offsets;
    }

    public function getGroupInstanceId(): ?string
    {
        return $this->groupInstanceId;
    }

    public function getMemberId(): string
    {
        return $this->memberId;
    }

    public function getFetchOffset(int $partition): int
    {
        if (!isset($this->offsets[$partition])) {
            throw new \RuntimeException(sprintf('Partition %s does not exists', $partition));
        }

        return $this->offsets[$partition];
    }

    public function addFetchOffset(int $partition, int $offset = 1): void
    {
        if (!isset($this->offsets[$partition])) {
            throw new \RuntimeException(sprintf('Partition %s does not exists', $partition));
        }
        $this->offsets[$partition] += $offset;
    }

    public function saveOffsets(int $partition, int $retry = 0): void
    {
        $request = new OffsetCommitRequest();
        $request->setGroupId($this->groupId);
        $request->setGroupInstanceId($this->groupInstanceId);
        $request->setMemberId($this->memberId);
        $request->setGenerationId($this->generationId);
        $topic = (new OffsetCommitRequestTopic())->setName($this->topic);
        $request->setTopics([$topic]);

        $timestamp = (int) (microtime(true) * 1000);
        $offset = $this->getFetchOffset($partition);
        $topic->setPartitions([
            (new OffsetCommitRequestPartition())->setPartitionIndex($partition)->setCommittedOffset($offset)->setCommitTimestamp($timestamp)->setCommittedMetadata($this->metadatas[$partition]),
        ]);

        $broker = $this->broker;
        for ($i = 0; $i <= $retry; ++$i) {
            /** @var OffsetCommitResponse $response */
            $response = $broker->getClientByBrokerId($this->coordinatorNodeId)->sendRecv($request);
            foreach ($response->getTopics() as $topic) {
                foreach ($topic->getPartitions() as $topicPartition) {
                    $errorCode = $topicPartition->getErrorCode();
                    if (ErrorCode::success($errorCode)) {
                        return;
                    }
                    if (ErrorCode::canRetry($errorCode)) {
                        continue 3;
                    }
                    ErrorCode::check($errorCode);
                }
            }
        }
    }

    public function getGroupId(): string
    {
        return $this->groupId;
    }

    public function getGenerationId(): int
    {
        return $this->generationId;
    }
}
