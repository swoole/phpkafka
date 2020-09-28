<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\UpdateMetadata;

use Longyan\Kafka\Protocol\AbstractStruct;
use Longyan\Kafka\Protocol\ProtocolField;

class UpdateMetadataPartitionState extends AbstractStruct
{
    /**
     * In older versions of this RPC, the topic name.
     *
     * @var string
     */
    protected $topicName;

    /**
     * The partition index.
     *
     * @var int
     */
    protected $partitionIndex;

    /**
     * The controller epoch.
     *
     * @var int
     */
    protected $controllerEpoch;

    /**
     * The ID of the broker which is the current partition leader.
     *
     * @var int
     */
    protected $brokerId;

    /**
     * The leader epoch of this partition.
     *
     * @var int
     */
    protected $leaderEpoch;

    /**
     * The brokers which are in the ISR for this partition.
     *
     * @var int32[]
     */
    protected $brokerId = [];

    /**
     * The Zookeeper version.
     *
     * @var int
     */
    protected $zkVersion;

    /**
     * All the replicas of this partition.
     *
     * @var int32[]
     */
    protected $brokerId = [];

    /**
     * The replicas of this partition which are offline.
     *
     * @var int32[]
     */
    protected $brokerId = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('topicName', 'string', false, [0, 1, 2, 3, 4], [6], [], [], null),
                new ProtocolField('partitionIndex', 'int32', false, [0, 1, 2, 3, 4, 5, 6], [6], [], [], null),
                new ProtocolField('controllerEpoch', 'int32', false, [0, 1, 2, 3, 4, 5, 6], [6], [], [], null),
                new ProtocolField('brokerId', 'int32', false, [0, 1, 2, 3, 4, 5, 6], [6], [], [], null),
                new ProtocolField('leaderEpoch', 'int32', false, [0, 1, 2, 3, 4, 5, 6], [6], [], [], null),
                new ProtocolField('brokerId', 'int32', true, [0, 1, 2, 3, 4, 5, 6], [6], [], [], null),
                new ProtocolField('zkVersion', 'int32', false, [0, 1, 2, 3, 4, 5, 6], [6], [], [], null),
                new ProtocolField('brokerId', 'int32', true, [0, 1, 2, 3, 4, 5, 6], [6], [], [], null),
                new ProtocolField('brokerId', 'int32', true, [4, 5, 6], [6], [], [], null),
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

    public function getBrokerId(): int
    {
        return $this->brokerId;
    }

    public function setBrokerId(int $brokerId): self
    {
        $this->brokerId = $brokerId;

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
     * @return int32[]
     */
    public function getBrokerId(): array
    {
        return $this->brokerId;
    }

    /**
     * @param int32[] $brokerId
     */
    public function setBrokerId(array $brokerId): self
    {
        $this->brokerId = $brokerId;

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
     * @return int32[]
     */
    public function getBrokerId(): array
    {
        return $this->brokerId;
    }

    /**
     * @param int32[] $brokerId
     */
    public function setBrokerId(array $brokerId): self
    {
        $this->brokerId = $brokerId;

        return $this;
    }

    /**
     * @return int32[]
     */
    public function getBrokerId(): array
    {
        return $this->brokerId;
    }

    /**
     * @param int32[] $brokerId
     */
    public function setBrokerId(array $brokerId): self
    {
        $this->brokerId = $brokerId;

        return $this;
    }
}
