<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\AlterReplicaLogDirs;

use Longyan\Kafka\Protocol\AbstractStruct;
use Longyan\Kafka\Protocol\ProtocolField;

class AlterReplicaLogDirTopicResult extends AbstractStruct
{
    /**
     * The name of the topic.
     *
     * @var string
     */
    protected $topicName;

    /**
     * The results for each partition.
     *
     * @var AlterReplicaLogDirPartitionResult[]
     */
    protected $partitions = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('topicName', 'string', false, [0, 1], [], [], [], null),
                new ProtocolField('partitions', AlterReplicaLogDirPartitionResult::class, true, [0, 1], [], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [];
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
     * @return AlterReplicaLogDirPartitionResult[]
     */
    public function getPartitions(): array
    {
        return $this->partitions;
    }

    /**
     * @param AlterReplicaLogDirPartitionResult[] $partitions
     */
    public function setPartitions(array $partitions): self
    {
        $this->partitions = $partitions;

        return $this;
    }
}
