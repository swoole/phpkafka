<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\StopReplica;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolField;

class StopReplicaTopicV1 extends AbstractStruct
{
    /**
     * The topic name.
     *
     * @var string
     */
    protected $name = '';

    /**
     * The partition indexes.
     *
     * @var int[]
     */
    protected $partitionIndexes = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('name', 'string', false, [1, 2], [2, 3], [], [], null),
                new ProtocolField('partitionIndexes', 'int32', true, [1, 2], [2, 3], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [2, 3];
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
