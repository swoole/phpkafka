<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\UpdateMetadata;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolField;

class UpdateMetadataTopicState extends AbstractStruct
{
    /**
     * The topic name.
     *
     * @var string
     */
    protected $topicName = '';

    /**
     * The partition that we would like to update.
     *
     * @var UpdateMetadataPartitionState[]
     */
    protected $partitionStates = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('topicName', 'string', false, [5, 6], [6], [], [], null),
                new ProtocolField('partitionStates', UpdateMetadataPartitionState::class, true, [5, 6], [6], [], [], null),
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

    /**
     * @return UpdateMetadataPartitionState[]
     */
    public function getPartitionStates(): array
    {
        return $this->partitionStates;
    }

    /**
     * @param UpdateMetadataPartitionState[] $partitionStates
     */
    public function setPartitionStates(array $partitionStates): self
    {
        $this->partitionStates = $partitionStates;

        return $this;
    }
}
