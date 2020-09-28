<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\Produce;

use Longyan\Kafka\Protocol\AbstractStruct;
use Longyan\Kafka\Protocol\ProtocolField;

class PartitionProduceData extends AbstractStruct
{
    /**
     * The partition index.
     *
     * @var int
     */
    protected $partitionIndex;

    /**
     * The record data to be produced.
     *
     * @var string|null
     */
    protected $records;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('partitionIndex', 'int32', false, [0, 1, 2, 3, 4, 5, 6, 7, 8], [], [], [], null),
                new ProtocolField('records', 'bytes', false, [0, 1, 2, 3, 4, 5, 6, 7, 8], [], [0, 1, 2, 3, 4, 5, 6, 7, 8], [], null),
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

    public function getRecords(): ?string
    {
        return $this->records;
    }

    public function setRecords(?string $records): self
    {
        $this->records = $records;

        return $this;
    }
}
