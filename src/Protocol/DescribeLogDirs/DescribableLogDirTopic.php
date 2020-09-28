<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\DescribeLogDirs;

use Longyan\Kafka\Protocol\AbstractStruct;
use Longyan\Kafka\Protocol\ProtocolField;

class DescribableLogDirTopic extends AbstractStruct
{
    /**
     * The topic name.
     *
     * @var string
     */
    protected $topicName;

    /**
     * The partition indxes.
     *
     * @var int32[]
     */
    protected $partitionIndex = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('topicName', 'string', false, [0, 1, 2], [2], [], [], null),
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
     * @return int32[]
     */
    public function getPartitionIndex(): array
    {
        return $this->partitionIndex;
    }

    /**
     * @param int32[] $partitionIndex
     */
    public function setPartitionIndex(array $partitionIndex): self
    {
        $this->partitionIndex = $partitionIndex;

        return $this;
    }
}
