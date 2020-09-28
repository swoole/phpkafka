<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\ElectLeaders;

use Longyan\Kafka\Protocol\AbstractStruct;
use Longyan\Kafka\Protocol\ProtocolField;

class TopicPartitions extends AbstractStruct
{
    /**
     * The name of a topic.
     *
     * @var string
     */
    protected $topicName;

    /**
     * The partitions of this topic whose leader should be elected.
     *
     * @var int32[]
     */
    protected $partitionId = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('topicName', 'string', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('partitionId', 'int32', true, [0, 1, 2], [2], [], [], null),
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
    public function getPartitionId(): array
    {
        return $this->partitionId;
    }

    /**
     * @param int32[] $partitionId
     */
    public function setPartitionId(array $partitionId): self
    {
        $this->partitionId = $partitionId;

        return $this;
    }
}
