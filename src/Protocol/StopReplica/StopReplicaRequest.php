<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\StopReplica;

use longlang\phpkafka\Protocol\AbstractRequest;
use longlang\phpkafka\Protocol\ProtocolField;

class StopReplicaRequest extends AbstractRequest
{
    /**
     * The controller id.
     *
     * @var int
     */
    protected $controllerId = 0;

    /**
     * The controller epoch.
     *
     * @var int
     */
    protected $controllerEpoch = 0;

    /**
     * The broker epoch.
     *
     * @var int
     */
    protected $brokerEpoch = -1;

    /**
     * Whether these partitions should be deleted.
     *
     * @var bool
     */
    protected $deletePartitions = false;

    /**
     * The partitions to stop.
     *
     * @var StopReplicaPartitionV0[]
     */
    protected $ungroupedPartitions = [];

    /**
     * The topics to stop.
     *
     * @var StopReplicaTopicV1[]
     */
    protected $topics = [];

    /**
     * Each topic.
     *
     * @var StopReplicaTopicState[]
     */
    protected $topicStates = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('controllerId', 'int32', false, [0, 1, 2, 3], [2, 3], [], [], null),
                new ProtocolField('controllerEpoch', 'int32', false, [0, 1, 2, 3], [2, 3], [], [], null),
                new ProtocolField('brokerEpoch', 'int64', false, [1, 2, 3], [2, 3], [], [], null),
                new ProtocolField('deletePartitions', 'bool', false, [0, 1, 2], [2, 3], [], [], null),
                new ProtocolField('ungroupedPartitions', StopReplicaPartitionV0::class, true, [0], [2, 3], [], [], null),
                new ProtocolField('topics', StopReplicaTopicV1::class, true, [1, 2], [2, 3], [], [], null),
                new ProtocolField('topicStates', StopReplicaTopicState::class, true, [3], [2, 3], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 5;
    }

    public function getMaxSupportedVersion(): int
    {
        return 3;
    }

    public function getFlexibleVersions(): array
    {
        return [2, 3];
    }

    public function getControllerId(): int
    {
        return $this->controllerId;
    }

    public function setControllerId(int $controllerId): self
    {
        $this->controllerId = $controllerId;

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

    public function getBrokerEpoch(): int
    {
        return $this->brokerEpoch;
    }

    public function setBrokerEpoch(int $brokerEpoch): self
    {
        $this->brokerEpoch = $brokerEpoch;

        return $this;
    }

    public function getDeletePartitions(): bool
    {
        return $this->deletePartitions;
    }

    public function setDeletePartitions(bool $deletePartitions): self
    {
        $this->deletePartitions = $deletePartitions;

        return $this;
    }

    /**
     * @return StopReplicaPartitionV0[]
     */
    public function getUngroupedPartitions(): array
    {
        return $this->ungroupedPartitions;
    }

    /**
     * @param StopReplicaPartitionV0[] $ungroupedPartitions
     */
    public function setUngroupedPartitions(array $ungroupedPartitions): self
    {
        $this->ungroupedPartitions = $ungroupedPartitions;

        return $this;
    }

    /**
     * @return StopReplicaTopicV1[]
     */
    public function getTopics(): array
    {
        return $this->topics;
    }

    /**
     * @param StopReplicaTopicV1[] $topics
     */
    public function setTopics(array $topics): self
    {
        $this->topics = $topics;

        return $this;
    }

    /**
     * @return StopReplicaTopicState[]
     */
    public function getTopicStates(): array
    {
        return $this->topicStates;
    }

    /**
     * @param StopReplicaTopicState[] $topicStates
     */
    public function setTopicStates(array $topicStates): self
    {
        $this->topicStates = $topicStates;

        return $this;
    }
}
