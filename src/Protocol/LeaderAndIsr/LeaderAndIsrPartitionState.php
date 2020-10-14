<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\LeaderAndIsr;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolField;

class LeaderAndIsrPartitionState extends AbstractStruct
{
    /**
     * The topic name.  This is only present in v0 or v1.
     *
     * @var string
     */
    protected $topicName = '';

    /**
     * The partition index.
     *
     * @var int
     */
    protected $partitionIndex = 0;

    /**
     * The controller epoch.
     *
     * @var int
     */
    protected $controllerEpoch = 0;

    /**
     * The broker ID of the leader.
     *
     * @var int
     */
    protected $leader = 0;

    /**
     * The leader epoch.
     *
     * @var int
     */
    protected $leaderEpoch = 0;

    /**
     * The in-sync replica IDs.
     *
     * @var int[]
     */
    protected $isr = [];

    /**
     * The ZooKeeper version.
     *
     * @var int
     */
    protected $zkVersion = 0;

    /**
     * The replica IDs.
     *
     * @var int[]
     */
    protected $replicas = [];

    /**
     * The replica IDs that we are adding this partition to, or null if no replicas are being added.
     *
     * @var int[]
     */
    protected $addingReplicas = [];

    /**
     * The replica IDs that we are removing this partition from, or null if no replicas are being removed.
     *
     * @var int[]
     */
    protected $removingReplicas = [];

    /**
     * Whether the replica should have existed on the broker or not.
     *
     * @var bool
     */
    protected $isNew = false;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('topicName', 'string', false, [0, 1], [4], [], [], null),
                new ProtocolField('partitionIndex', 'int32', false, [0, 1, 2, 3, 4], [4], [], [], null),
                new ProtocolField('controllerEpoch', 'int32', false, [0, 1, 2, 3, 4], [4], [], [], null),
                new ProtocolField('leader', 'int32', false, [0, 1, 2, 3, 4], [4], [], [], null),
                new ProtocolField('leaderEpoch', 'int32', false, [0, 1, 2, 3, 4], [4], [], [], null),
                new ProtocolField('isr', 'int32', true, [0, 1, 2, 3, 4], [4], [], [], null),
                new ProtocolField('zkVersion', 'int32', false, [0, 1, 2, 3, 4], [4], [], [], null),
                new ProtocolField('replicas', 'int32', true, [0, 1, 2, 3, 4], [4], [], [], null),
                new ProtocolField('addingReplicas', 'int32', true, [3, 4], [4], [], [], null),
                new ProtocolField('removingReplicas', 'int32', true, [3, 4], [4], [], [], null),
                new ProtocolField('isNew', 'bool', false, [1, 2, 3, 4], [4], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [4];
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

    public function getPartitionIndex(): int
    {
        return $this->partitionIndex;
    }

    public function setPartitionIndex(int $partitionIndex): self
    {
        $this->partitionIndex = $partitionIndex;

        return $this;
    }

    public function getControllerEpoch(): int
    {
        return $this->controllerEpoch;
    }

    public function setControllerEpoch(int $controllerEpoch): self
    {
        $this->controllerEpoch = $controllerEpoch;

        return $this;
    }

    public function getLeader(): int
    {
        return $this->leader;
    }

    public function setLeader(int $leader): self
    {
        $this->leader = $leader;

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

    /**
     * @return int[]
     */
    public function getIsr(): array
    {
        return $this->isr;
    }

    /**
     * @param int[] $isr
     */
    public function setIsr(array $isr): self
    {
        $this->isr = $isr;

        return $this;
    }

    public function getZkVersion(): int
    {
        return $this->zkVersion;
    }

    public function setZkVersion(int $zkVersion): self
    {
        $this->zkVersion = $zkVersion;

        return $this;
    }

    /**
     * @return int[]
     */
    public function getReplicas(): array
    {
        return $this->replicas;
    }

    /**
     * @param int[] $replicas
     */
    public function setReplicas(array $replicas): self
    {
        $this->replicas = $replicas;

        return $this;
    }

    /**
     * @return int[]
     */
    public function getAddingReplicas(): array
    {
        return $this->addingReplicas;
    }

    /**
     * @param int[] $addingReplicas
     */
    public function setAddingReplicas(array $addingReplicas): self
    {
        $this->addingReplicas = $addingReplicas;

        return $this;
    }

    /**
     * @return int[]
     */
    public function getRemovingReplicas(): array
    {
        return $this->removingReplicas;
    }

    /**
     * @param int[] $removingReplicas
     */
    public function setRemovingReplicas(array $removingReplicas): self
    {
        $this->removingReplicas = $removingReplicas;

        return $this;
    }

    public function getIsNew(): bool
    {
        return $this->isNew;
    }

    public function setIsNew(bool $isNew): self
    {
        $this->isNew = $isNew;

        return $this;
    }
}
