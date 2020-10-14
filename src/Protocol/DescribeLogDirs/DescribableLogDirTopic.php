<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\DescribeLogDirs;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolField;

class DescribableLogDirTopic extends AbstractStruct
{
    /**
     * The topic name.
     *
     * @var string
     */
    protected $topic = '';

    /**
     * The partition indxes.
     *
     * @var int[]
     */
    protected $partitionIndex = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('topic', 'string', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('partitionIndex', 'int32', true, [0, 1, 2], [2], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [2];
    }

    public function getTopic(): string
    {
        return $this->topic;
    }

    public function setTopic(string $topic): self
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * @return int[]
     */
    public function getPartitionIndex(): array
    {
        return $this->partitionIndex;
    }

    /**
     * @param int[] $partitionIndex
     */
    public function setPartitionIndex(array $partitionIndex): self
    {
        $this->partitionIndex = $partitionIndex;

        return $this;
    }
}
