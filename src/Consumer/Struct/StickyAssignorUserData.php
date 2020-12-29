<?php

declare(strict_types=1);

namespace longlang\phpkafka\Consumer\Struct;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolField;

class StickyAssignorUserData extends AbstractStruct
{
    /**
     * @var TopicPartition[]
     */
    protected $partitions = [];

    /**
     * @var int|null
     */
    protected $generation;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('partitions', TopicPartition::class, true, [0], [], [], [], null),
                new ProtocolField('generation', 'int32', false, [0, 1], [], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getMaxSupportedVersion(): int
    {
        return 0;
    }

    public function getFlexibleVersions(): array
    {
        return [];
    }

    /**
     * @return TopicPartition[]
     */
    public function getPartitions(): array
    {
        return $this->partitions;
    }

    /**
     * @param TopicPartition[] $partitions
     */
    public function setPartitions(array $partitions): self
    {
        $this->partitions = $partitions;

        return $this;
    }

    public function getGeneration(): ?int
    {
        return $this->generation;
    }

    public function setGeneration(?int $generation): self
    {
        $this->generation = $generation;

        return $this;
    }
}
