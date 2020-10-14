<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\Metadata;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolField;

class MetadataResponsePartition extends AbstractStruct
{
    /**
     * The partition error, or 0 if there was no error.
     *
     * @var int
     */
    protected $errorCode = 0;

    /**
     * The partition index.
     *
     * @var int
     */
    protected $partitionIndex = 0;

    /**
     * The ID of the leader broker.
     *
     * @var int
     */
    protected $leaderId = 0;

    /**
     * The leader epoch of this partition.
     *
     * @var int
     */
    protected $leaderEpoch = -1;

    /**
     * The set of all nodes that host this partition.
     *
     * @var int[]
     */
    protected $replicaNodes = [];

    /**
     * The set of nodes that are in sync with the leader for this partition.
     *
     * @var int[]
     */
    protected $isrNodes = [];

    /**
     * The set of offline replicas of this partition.
     *
     * @var int[]
     */
    protected $offlineReplicas = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('errorCode', 'int16', false, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9], [9], [], [], null),
                new ProtocolField('partitionIndex', 'int32', false, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9], [9], [], [], null),
                new ProtocolField('leaderId', 'int32', false, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9], [9], [], [], null),
                new ProtocolField('leaderEpoch', 'int32', false, [7, 8, 9], [9], [], [], null),
                new ProtocolField('replicaNodes', 'int32', true, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9], [9], [], [], null),
                new ProtocolField('isrNodes', 'int32', true, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9], [9], [], [], null),
                new ProtocolField('offlineReplicas', 'int32', true, [5, 6, 7, 8, 9], [9], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [9];
    }

    public function getErrorCode(): int
    {
        return $this->errorCode;
    }

    public function setErrorCode(int $errorCode): self
    {
        $this->errorCode = $errorCode;

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

    public function getLeaderId(): int
    {
        return $this->leaderId;
    }

    public function setLeaderId(int $leaderId): self
    {
        $this->leaderId = $leaderId;

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
    public function getReplicaNodes(): array
    {
        return $this->replicaNodes;
    }

    /**
     * @param int[] $replicaNodes
     */
    public function setReplicaNodes(array $replicaNodes): self
    {
        $this->replicaNodes = $replicaNodes;

        return $this;
    }

    /**
     * @return int[]
     */
    public function getIsrNodes(): array
    {
        return $this->isrNodes;
    }

    /**
     * @param int[] $isrNodes
     */
    public function setIsrNodes(array $isrNodes): self
    {
        $this->isrNodes = $isrNodes;

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
