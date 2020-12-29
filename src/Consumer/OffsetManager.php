<?php

declare(strict_types=1);

namespace longlang\phpkafka\Consumer;

use longlang\phpkafka\Client\ClientInterface;
use longlang\phpkafka\Protocol\ErrorCode;
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
     * @var ClientInterface
     */
    protected $client;

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

    public function __construct(ClientInterface $client, string $topic, array $partitions, string $groupId, ?string $groupInstanceId, string $memberId, int $generationId)
    {
        $this->client = $client;
        $this->topic = $topic;
        $this->partitions = $partitions;
        $this->groupId = $groupId;
        $this->groupInstanceId = $groupInstanceId;
        $this->memberId = $memberId;
        $this->generationId = $generationId;
    }

    public function updateOffsets(int $retry = 0)
    {
        $request = new OffsetFetchRequest();
        $request->setGroupId($this->groupId);
        $request->setTopics([
            (new OffsetFetchRequestTopic())->setName($this->topic)->setPartitionIndexes($this->partitions),
        ]);

        /** @var OffsetFetchResponse $response */
        $response = KafkaUtil::retry($this->client, $request, $retry, 0);

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

    public function getClient(): ClientInterface
    {
        return $this->client;
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
            throw new \RuntimeException(sprintf('Partition %s doses not exists', $partition));
        }

        return $this->offsets[$partition];
    }

    public function addFetchOffset(int $partition, int $offset = 1)
    {
        if (!isset($this->offsets[$partition])) {
            throw new \RuntimeException(sprintf('Partition %s doses not exists', $partition));
        }
        $this->offsets[$partition] += $offset;
    }

    public function saveOffsets(?int $partition = null, int $retry = 0)
    {
        $request = new OffsetCommitRequest();
        $request->setGroupId($this->groupId);
        $request->setGroupInstanceId($this->groupInstanceId);
        $request->setMemberId($this->memberId);
        $request->setGenerationId($this->generationId);
        $topic = (new OffsetCommitRequestTopic())->setName($this->topic);
        $request->setTopics([$topic]);
        $partitions = [];
        $timestamp = (int) (microtime(true) * 1000);
        if (null === $partition) {
            foreach ($this->partitions as $partition => $offset) {
                $partitions[] = (new OffsetCommitRequestPartition())->setPartitionIndex($partition)->setCommittedOffset($offset)->setCommitTimestamp($timestamp)->setCommittedMetadata($this->metadatas[$partition]);
            }
        } else {
            $offset = $this->getFetchOffset($partition);
            $partitions[] = (new OffsetCommitRequestPartition())->setPartitionIndex($partition)->setCommittedOffset($offset)->setCommitTimestamp($timestamp)->setCommittedMetadata($this->metadatas[$partition]);
        }
        $topic->setPartitions($partitions);

        for ($i = 0; $i <= $retry; ++$i) {
            /** @var OffsetCommitResponse $response */
            $response = $this->client->sendRecv($request);
            foreach ($response->getTopics() as $topic) {
                foreach ($topic->getPartitions() as $partition) {
                    $errorCode = $partition->getErrorCode();
                    if (!ErrorCode::success($errorCode)) {
                        if ($retry > 0 && ErrorCode::canRetry($errorCode)) {
                            continue 3;
                        }
                        ErrorCode::check($errorCode);
                    }
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
