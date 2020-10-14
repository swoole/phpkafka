<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\StopReplica;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolField;

class StopReplicaPartitionState extends AbstractStruct
{
    /**
     * The partition index.
     *
     * @var int
     */
    protected $partitionIndex = 0;

    /**
     * The leader epoch.
     *
     * @var int
     */
    protected $leaderEpoch = -1;

    /**
     * Whether this partition should be deleted.
     *
     * @var bool
     */
    protected $deletePartition = false;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('partitionIndex', 'int32', false, [3], [2, 3], [], [], null),
                new ProtocolField('leaderEpoch', 'int32', false, [3], [2, 3], [], [], null),
                new ProtocolField('deletePartition', 'bool', false, [3], [2, 3], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [2, 3];
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

    public function getLeaderEpoch(): int
    {
        return $this->leaderEpoch;
    }

    public function setLeaderEpoch(int $leaderEpoch): self
    {
        $this->leaderEpoch = $leaderEpoch;

        return $this;
    }

    public function getDeletePartition(): bool
    {
        return $this->deletePartition;
    }

    public function setDeletePartition(bool $deletePartition): self
    {
        $this->deletePartition = $deletePartition;

        return $this;
    }
}
