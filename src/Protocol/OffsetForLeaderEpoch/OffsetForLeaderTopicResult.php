<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\OffsetForLeaderEpoch;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolField;

class OffsetForLeaderTopicResult extends AbstractStruct
{
    /**
     * The topic name.
     *
     * @var string
     */
    protected $name = '';

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
                new ProtocolField('name', 'string', false, [0, 1, 2, 3], [], [], [], null),
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

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
