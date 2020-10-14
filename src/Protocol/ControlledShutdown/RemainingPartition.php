<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\ControlledShutdown;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolField;

class RemainingPartition extends AbstractStruct
{
    /**
     * The name of the topic.
     *
     * @var string
     */
    protected $topicName = '';

    /**
     * The index of the partition.
     *
     * @var int
     */
    protected $partitionIndex = 0;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('topicName', 'string', false, [0, 1, 2, 3], [3], [], [], null),
                new ProtocolField('partitionIndex', 'int32', false, [0, 1, 2, 3], [3], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [3];
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
}
