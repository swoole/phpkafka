<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\DescribeLogDirs;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolField;

class DescribeLogDirsPartition extends AbstractStruct
{
    /**
     * The partition index.
     *
     * @var int
     */
    protected $partitionIndex = 0;

    /**
     * The size of the log segments in this partition in bytes.
     *
     * @var int
     */
    protected $partitionSize = 0;

    /**
     * The lag of the log's LEO w.r.t. partition's HW (if it is the current log for the partition) or current replica's LEO (if it is the future log for the partition).
     *
     * @var int
     */
    protected $offsetLag = 0;

    /**
     * True if this log is created by AlterReplicaLogDirsRequest and will replace the current log of the replica in the future.
     *
     * @var bool
     */
    protected $isFutureKey = false;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('partitionIndex', 'int32', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('partitionSize', 'int64', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('offsetLag', 'int64', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('isFutureKey', 'bool', false, [0, 1, 2], [2], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [2];
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

    public function getPartitionSize(): int
    {
        return $this->partitionSize;
    }

    public function setPartitionSize(int $partitionSize): self
    {
        $this->partitionSize = $partitionSize;

        return $this;
    }

    public function getOffsetLag(): int
    {
        return $this->offsetLag;
    }

    public function setOffsetLag(int $offsetLag): self
    {
        $this->offsetLag = $offsetLag;

        return $this;
    }

    public function getIsFutureKey(): bool
    {
        return $this->isFutureKey;
    }

    public function setIsFutureKey(bool $isFutureKey): self
    {
        $this->isFutureKey = $isFutureKey;

        return $this;
    }
}
