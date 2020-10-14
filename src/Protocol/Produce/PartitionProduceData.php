<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\Produce;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolField;

class PartitionProduceData extends AbstractStruct
{
    /**
     * The partition index.
     *
     * @var int
     */
    protected $partitionIndex = 0;

    /**
     * The record data to be produced.
     *
     * @var \longlang\phpkafka\Protocol\RecordBatch\RecordBatch|null
     */
    protected $records = null;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('partitionIndex', 'int32', false, [0, 1, 2, 3, 4, 5, 6, 7, 8], [], [], [], null),
                new ProtocolField('records', '\longlang\phpkafka\Protocol\RecordBatch\RecordBatch', false, [0, 1, 2, 3, 4, 5, 6, 7, 8], [], [0, 1, 2, 3, 4, 5, 6, 7, 8], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [];
    }

    public function getPartitionIndex(): int
    {
        return $this->partitionIndex;
    }

    public function setPartitionIndex(int $partitionIndex): self
    {
        $this->partitionIndex = $partitionIndex;

        return $this;
    }

    public function getRecords(): ?\longlang\phpkafka\Protocol\RecordBatch\RecordBatch
    {
        return $this->records;
    }

    public function setRecords(?\longlang\phpkafka\Protocol\RecordBatch\RecordBatch $records): self
    {
        $this->records = $records;

        return $this;
    }
}
