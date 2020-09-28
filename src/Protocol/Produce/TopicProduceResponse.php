<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\Produce;

use Longyan\Kafka\Protocol\AbstractStruct;
use Longyan\Kafka\Protocol\ProtocolField;

class TopicProduceResponse extends AbstractStruct
{
    /**
     * The topic name.
     *
     * @var string
     */
    protected $topicName;

    /**
     * Each partition that we produced to within the topic.
     *
     * @var PartitionProduceResponse[]
     */
    protected $partitions = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('topicName', 'string', false, [0, 1, 2, 3, 4, 5, 6, 7, 8], [], [], [], null),
                new ProtocolField('partitions', PartitionProduceResponse::class, true, [0, 1, 2, 3, 4, 5, 6, 7, 8], [], [], [], null),
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
     * @return PartitionProduceResponse[]
     */
    public function getPartitions(): array
    {
        return $this->partitions;
    }

    /**
     * @param PartitionProduceResponse[] $partitions
     */
    public function setPartitions(array $partitions): self
    {
        $this->partitions = $partitions;

        return $this;
    }
}
