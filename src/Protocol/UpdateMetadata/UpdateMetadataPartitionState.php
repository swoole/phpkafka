<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\UpdateMetadata;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolField;

class UpdateMetadataPartitionState extends AbstractStruct
{
    /**
     * In older versions of this RPC, the topic name.
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
     * The ID of the broker which is the current partition leader.
     *
     * @var int
     */
    protected $leader = 0;

    /**
     * The leader epoch of this partition.
     *
     * @var int
     */
    protected $leaderEpoch = 0;

    /**
     * The brokers which are in the ISR for this partition.
     *
     * @var int[]
     */
    protected $isr = [];

    /**
     * The Zookeeper version.
     *
     * @var int
     */
    protected $zkVersion = 0;

    /**
     * All the replicas of this partition.
     *
     * @var int[]
     */
    protected $replicas = [];

    /**
     * The replicas of this partition which are offline.
     *
     * @var int[]
     */
    protected $offlineReplicas = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('topicName', 'string', false, [0, 1, 2, 3, 4], [6], [], [], null),
                new ProtocolField('partitionIndex', 'int32', false, [0, 1, 2, 3, 4, 5, 6], [6], [], [], null),
                new ProtocolField('controllerEpoch', 'int32', false, [0, 1, 2, 3, 4, 5, 6], [6], [], [], null),
                new ProtocolField('leader', 'int32', false, [0, 1, 2, 3, 4, 5, 6], [6], [], [], null),
                new ProtocolField('leaderEpoch', 'int32', false, [0, 1, 2, 3, 4, 5, 6], [6], [], [], null),
                new ProtocolField('isr', 'int32', true, [0, 1, 2, 3, 4, 5, 6], [6], [], [], null),
                new ProtocolField('zkVersion', 'int32', false, [0, 1, 2, 3, 4, 5, 6], [6], [], [], null),
                new ProtocolField('replicas', 'int32', true, [0, 1, 2, 3, 4, 5, 6], [6], [], [], null),
                new ProtocolField('offlineReplicas', 'int32', true, [4, 5, 6], [6], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [6];
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
    public function getOfflineReplicas(): array
    {
        return $this->offlineReplicas;
    }

    /**
     * @param int[] $offlineReplicas
     */
    public function setOfflineReplicas(array $offlineReplicas): self
    {
        $this->offlineReplicas = $offlineReplicas;

        return $this;
    }
}
