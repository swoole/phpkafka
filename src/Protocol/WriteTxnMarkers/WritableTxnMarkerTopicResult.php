<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\WriteTxnMarkers;

use Longyan\Kafka\Protocol\AbstractStruct;
use Longyan\Kafka\Protocol\ProtocolField;

class WritableTxnMarkerTopicResult extends AbstractStruct
{
    /**
     * The topic name.
     *
     * @var string
     */
    protected $topicName;

    /**
     * The results by partition.
     *
     * @var WritableTxnMarkerPartitionResult[]
     */
    protected $partitions = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('topicName', 'string', false, [0], [], [], [], null),
                new ProtocolField('partitions', WritableTxnMarkerPartitionResult::class, true, [0], [], [], [], null),
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
     * @return WritableTxnMarkerPartitionResult[]
     */
    public function getPartitions(): array
    {
        return $this->partitions;
    }

    /**
     * @param WritableTxnMarkerPartitionResult[] $partitions
     */
    public function setPartitions(array $partitions): self
    {
        $this->partitions = $partitions;

        return $this;
    }
}
