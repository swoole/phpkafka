<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\AddPartitionsToTxn;

use Longyan\Kafka\Protocol\AbstractStruct;
use Longyan\Kafka\Protocol\ProtocolField;

class AddPartitionsToTxnTopic extends AbstractStruct
{
    /**
     * The name of the topic.
     *
     * @var string
     */
    protected $topicName;

    /**
     * The partition indexes to add to the transaction.
     *
     * @var int32[]
     */
    protected $partitions = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('topicName', 'string', false, [0, 1], [], [], [], null),
                new ProtocolField('partitions', 'int32', true, [0, 1], [], [], [], null),
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
     * @return int32[]
     */
    public function getPartitions(): array
    {
        return $this->partitions;
    }

    /**
     * @param int32[] $partitions
     */
    public function setPartitions(array $partitions): self
    {
        $this->partitions = $partitions;

        return $this;
    }
}
