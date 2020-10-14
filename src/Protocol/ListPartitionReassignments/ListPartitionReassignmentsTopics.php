<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\ListPartitionReassignments;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolField;

class ListPartitionReassignmentsTopics extends AbstractStruct
{
    /**
     * The topic name.
     *
     * @var string
     */
    protected $name = '';

    /**
     * The partitions to list partition reassignments for.
     *
     * @var int[]
     */
    protected $partitionIndexes = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('name', 'string', false, [0], [0], [], [], null),
                new ProtocolField('partitionIndexes', 'int32', true, [0], [0], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [0];
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
     * @return int[]
     */
    public function getPartitionIndexes(): array
    {
        return $this->partitionIndexes;
    }

    /**
     * @param int[] $partitionIndexes
     */
    public function setPartitionIndexes(array $partitionIndexes): self
    {
        $this->partitionIndexes = $partitionIndexes;

        return $this;
    }
}
