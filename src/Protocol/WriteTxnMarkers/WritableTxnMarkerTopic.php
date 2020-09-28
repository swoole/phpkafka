<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\WriteTxnMarkers;

use Longyan\Kafka\Protocol\AbstractStruct;
use Longyan\Kafka\Protocol\ProtocolField;

class WritableTxnMarkerTopic extends AbstractStruct
{
    /**
     * The topic name.
     *
     * @var string
     */
    protected $topicName;

    /**
     * The indexes of the partitions to write transaction markers for.
     *
     * @var int32[]
     */
    protected $partitionIndexes = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('topicName', 'string', false, [0], [], [], [], null),
                new ProtocolField('partitionIndexes', 'int32', true, [0], [], [], [], null),
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
    public function getPartitionIndexes(): array
    {
        return $this->partitionIndexes;
    }

    /**
     * @param int32[] $partitionIndexes
     */
    public function setPartitionIndexes(array $partitionIndexes): self
    {
        $this->partitionIndexes = $partitionIndexes;

        return $this;
    }
}
