<?php

declare(strict_types=1);

namespace longlang\phpkafka\Group\Struct;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolField;

class ConsumerGroupTopic extends AbstractStruct
{
    /**
     * @var string
     */
    protected $topicName;

    /**
     * @var array
     */
    protected $partitions = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('topicName', 'string', false, [0], [], [], [], null),
                new ProtocolField('partitions', 'int32', true, [0], [], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getMaxSupportedVersion(): int
    {
        return 0;
    }

    public function getFlexibleVersions(): array
    {
        return [];
    }

    public function getTopicName(): string
    {
        return $this->topicName;
    }

    public function setTopicName(string $topicName)
    {
        $this->topicName = $topicName;

        return $this;
    }

    public function getPartitions(): array
    {
        return $this->partitions;
    }

    public function setPartitions(array $partitions): self
    {
        $this->partitions = $partitions;

        return $this;
    }
}
