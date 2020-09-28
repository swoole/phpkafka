<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\DescribeLogDirs;

use Longyan\Kafka\Protocol\AbstractStruct;
use Longyan\Kafka\Protocol\ProtocolField;

class DescribeLogDirsTopic extends AbstractStruct
{
    /**
     * The topic name.
     *
     * @var string
     */
    protected $topicName;

    /**
     * @var DescribeLogDirsPartition[]
     */
    protected $partitions = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('topicName', 'string', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('partitions', DescribeLogDirsPartition::class, true, [0, 1, 2], [2], [], [], null),
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
     * @return DescribeLogDirsPartition[]
     */
    public function getPartitions(): array
    {
        return $this->partitions;
    }

    /**
     * @param DescribeLogDirsPartition[] $partitions
     */
    public function setPartitions(array $partitions): self
    {
        $this->partitions = $partitions;

        return $this;
    }
}
