<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\OffsetForLeaderEpoch;

use Longyan\Kafka\Protocol\AbstractStruct;
use Longyan\Kafka\Protocol\ProtocolField;

class OffsetForLeaderTopicResult extends AbstractStruct
{
    /**
     * The topic name.
     *
     * @var string
     */
    protected $topicName;

    /**
     * Each partition in the topic we fetched offsets for.
     *
     * @var OffsetForLeaderPartitionResult[]
     */
    protected $partitions = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('topicName', 'string', false, [0, 1, 2, 3], [], [], [], null),
                new ProtocolField('partitions', OffsetForLeaderPartitionResult::class, true, [0, 1, 2, 3], [], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [];
    }

    public function getTopicName(): string
    {
        return $this->topicName;
    }

    public function setTopicName(string $topicName): self
    {
        $this->topicName = $topicName;

        return $this;
    }

    /**
     * @return OffsetForLeaderPartitionResult[]
     */
    public function getPartitions(): array
    {
        return $this->partitions;
    }

    /**
     * @param OffsetForLeaderPartitionResult[] $partitions
     */
    public function setPartitions(array $partitions): self
    {
        $this->partitions = $partitions;

        return $this;
    }
}
