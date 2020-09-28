<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\ListOffset;

use Longyan\Kafka\Protocol\AbstractStruct;
use Longyan\Kafka\Protocol\ProtocolField;

class ListOffsetTopicResponse extends AbstractStruct
{
    /**
     * The topic name.
     *
     * @var string
     */
    protected $topicName;

    /**
     * Each partition in the response.
     *
     * @var ListOffsetPartitionResponse[]
     */
    protected $partitions = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('topicName', 'string', false, [0, 1, 2, 3, 4, 5], [], [], [], null),
                new ProtocolField('partitions', ListOffsetPartitionResponse::class, true, [0, 1, 2, 3, 4, 5], [], [], [], null),
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
     * @return ListOffsetPartitionResponse[]
     */
    public function getPartitions(): array
    {
        return $this->partitions;
    }

    /**
     * @param ListOffsetPartitionResponse[] $partitions
     */
    public function setPartitions(array $partitions): self
    {
        $this->partitions = $partitions;

        return $this;
    }
}
