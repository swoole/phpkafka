<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\OffsetCommit;

use Longyan\Kafka\Protocol\AbstractStruct;
use Longyan\Kafka\Protocol\ProtocolField;

class OffsetCommitRequestTopic extends AbstractStruct
{
    /**
     * The topic name.
     *
     * @var string
     */
    protected $topicName;

    /**
     * Each partition to commit offsets for.
     *
     * @var OffsetCommitRequestPartition[]
     */
    protected $partitions = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('topicName', 'string', false, [0, 1, 2, 3, 4, 5, 6, 7, 8], [8], [], [], null),
                new ProtocolField('partitions', OffsetCommitRequestPartition::class, true, [0, 1, 2, 3, 4, 5, 6, 7, 8], [8], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [8];
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
     * @return OffsetCommitRequestPartition[]
     */
    public function getPartitions(): array
    {
        return $this->partitions;
    }

    /**
     * @param OffsetCommitRequestPartition[] $partitions
     */
    public function setPartitions(array $partitions): self
    {
        $this->partitions = $partitions;

        return $this;
    }
}
