<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\AlterPartitionReassignments;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolField;

class ReassignablePartition extends AbstractStruct
{
    /**
     * The partition index.
     *
     * @var int
     */
    protected $partitionIndex = 0;

    /**
     * The replicas to place the partitions on, or null to cancel a pending reassignment for this partition.
     *
     * @var int[]|null
     */
    protected $replicas = null;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('partitionIndex', 'int32', false, [0], [0], [], [], null),
                new ProtocolField('replicas', 'int32', true, [0], [0], [0], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [0];
    }

    public function getPartitionIndex(): int
    {
        return $this->partitionIndex;
    }

    public function setPartitionIndex(int $partitionIndex): self
    {
        $this->partitionIndex = $partitionIndex;

        return $this;
    }

    /**
     * @return int[]|null
     */
    public function getReplicas(): ?array
    {
        return $this->replicas;
    }

    /**
     * @param int[]|null $replicas
     */
    public function setReplicas(?array $replicas): self
    {
        $this->replicas = $replicas;

        return $this;
    }
}
