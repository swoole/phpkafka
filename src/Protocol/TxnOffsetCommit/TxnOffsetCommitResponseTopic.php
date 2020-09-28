<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\TxnOffsetCommit;

use Longyan\Kafka\Protocol\AbstractStruct;
use Longyan\Kafka\Protocol\ProtocolField;

class TxnOffsetCommitResponseTopic extends AbstractStruct
{
    /**
     * The topic name.
     *
     * @var string
     */
    protected $topicName;

    /**
     * The responses for each partition in the topic.
     *
     * @var TxnOffsetCommitResponsePartition[]
     */
    protected $partitions = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('topicName', 'string', false, [0, 1, 2, 3], [3], [], [], null),
                new ProtocolField('partitions', TxnOffsetCommitResponsePartition::class, true, [0, 1, 2, 3], [3], [], [], null),
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

    /**
     * @return TxnOffsetCommitResponsePartition[]
     */
    public function getPartitions(): array
    {
        return $this->partitions;
    }

    /**
     * @param TxnOffsetCommitResponsePartition[] $partitions
     */
    public function setPartitions(array $partitions): self
    {
        $this->partitions = $partitions;

        return $this;
    }
}
