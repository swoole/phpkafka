<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\LeaderAndIsr;

use longlang\phpkafka\Protocol\AbstractRequest;
use longlang\phpkafka\Protocol\ProtocolField;

class LeaderAndIsrRequest extends AbstractRequest
{
    /**
     * The current controller ID.
     *
     * @var int
     */
    protected $controllerId = 0;

    /**
     * The current controller epoch.
     *
     * @var int
     */
    protected $controllerEpoch = 0;

    /**
     * The current broker epoch.
     *
     * @var int
     */
    protected $brokerEpoch = -1;

    /**
     * The state of each partition, in a v0 or v1 message.
     *
     * @var LeaderAndIsrPartitionState[]
     */
    protected $ungroupedPartitionStates = [];

    /**
     * Each topic.
     *
     * @var LeaderAndIsrTopicState[]
     */
    protected $topicStates = [];

    /**
     * The current live leaders.
     *
     * @var LeaderAndIsrLiveLeader[]
     */
    protected $liveLeaders = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('controllerId', 'int32', false, [0, 1, 2, 3, 4], [4], [], [], null),
                new ProtocolField('controllerEpoch', 'int32', false, [0, 1, 2, 3, 4], [4], [], [], null),
                new ProtocolField('brokerEpoch', 'int64', false, [2, 3, 4], [4], [], [], null),
                new ProtocolField('ungroupedPartitionStates', LeaderAndIsrPartitionState::class, true, [0, 1], [4], [], [], null),
                new ProtocolField('topicStates', LeaderAndIsrTopicState::class, true, [2, 3, 4], [4], [], [], null),
                new ProtocolField('liveLeaders', LeaderAndIsrLiveLeader::class, true, [0, 1, 2, 3, 4], [4], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 4;
    }

    public function getMaxSupportedVersion(): int
    {
        return 4;
    }

    public function getFlexibleVersions(): array
    {
        return [4];
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

    /**
     * @return LeaderAndIsrPartitionState[]
     */
    public function getUngroupedPartitionStates(): array
    {
        return $this->ungroupedPartitionStates;
    }

    /**
     * @param LeaderAndIsrPartitionState[] $ungroupedPartitionStates
     */
    public function setUngroupedPartitionStates(array $ungroupedPartitionStates): self
    {
        $this->ungroupedPartitionStates = $ungroupedPartitionStates;

        return $this;
    }

    /**
     * @return LeaderAndIsrTopicState[]
     */
    public function getTopicStates(): array
    {
        return $this->topicStates;
    }

    /**
     * @param LeaderAndIsrTopicState[] $topicStates
     */
    public function setTopicStates(array $topicStates): self
    {
        $this->topicStates = $topicStates;

        return $this;
    }

    /**
     * @return LeaderAndIsrLiveLeader[]
     */
    public function getLiveLeaders(): array
    {
        return $this->liveLeaders;
    }

    /**
     * @param LeaderAndIsrLiveLeader[] $liveLeaders
     */
    public function setLiveLeaders(array $liveLeaders): self
    {
        $this->liveLeaders = $liveLeaders;

        return $this;
    }
}
