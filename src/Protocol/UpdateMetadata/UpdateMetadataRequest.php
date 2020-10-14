<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\UpdateMetadata;

use longlang\phpkafka\Protocol\AbstractRequest;
use longlang\phpkafka\Protocol\ProtocolField;

class UpdateMetadataRequest extends AbstractRequest
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
     * In older versions of this RPC, each partition that we would like to update.
     *
     * @var UpdateMetadataPartitionState[]
     */
    protected $ungroupedPartitionStates = [];

    /**
     * In newer versions of this RPC, each topic that we would like to update.
     *
     * @var UpdateMetadataTopicState[]
     */
    protected $topicStates = [];

    /**
     * @var UpdateMetadataBroker[]
     */
    protected $liveBrokers = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('controllerId', 'int32', false, [0, 1, 2, 3, 4, 5, 6], [6], [], [], null),
                new ProtocolField('controllerEpoch', 'int32', false, [0, 1, 2, 3, 4, 5, 6], [6], [], [], null),
                new ProtocolField('brokerEpoch', 'int64', false, [5, 6], [6], [], [], null),
                new ProtocolField('ungroupedPartitionStates', UpdateMetadataPartitionState::class, true, [0, 1, 2, 3, 4], [6], [], [], null),
                new ProtocolField('topicStates', UpdateMetadataTopicState::class, true, [5, 6], [6], [], [], null),
                new ProtocolField('liveBrokers', UpdateMetadataBroker::class, true, [0, 1, 2, 3, 4, 5, 6], [6], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 6;
    }

    public function getMaxSupportedVersion(): int
    {
        return 6;
    }

    public function getFlexibleVersions(): array
    {
        return [6];
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
     * @return UpdateMetadataPartitionState[]
     */
    public function getUngroupedPartitionStates(): array
    {
        return $this->ungroupedPartitionStates;
    }

    /**
     * @param UpdateMetadataPartitionState[] $ungroupedPartitionStates
     */
    public function setUngroupedPartitionStates(array $ungroupedPartitionStates): self
    {
        $this->ungroupedPartitionStates = $ungroupedPartitionStates;

        return $this;
    }

    /**
     * @return UpdateMetadataTopicState[]
     */
    public function getTopicStates(): array
    {
        return $this->topicStates;
    }

    /**
     * @param UpdateMetadataTopicState[] $topicStates
     */
    public function setTopicStates(array $topicStates): self
    {
        $this->topicStates = $topicStates;

        return $this;
    }

    /**
     * @return UpdateMetadataBroker[]
     */
    public function getLiveBrokers(): array
    {
        return $this->liveBrokers;
    }

    /**
     * @param UpdateMetadataBroker[] $liveBrokers
     */
    public function setLiveBrokers(array $liveBrokers): self
    {
        $this->liveBrokers = $liveBrokers;

        return $this;
    }
}
