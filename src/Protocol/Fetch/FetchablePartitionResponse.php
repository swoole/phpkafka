<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\Fetch;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolField;

class FetchablePartitionResponse extends AbstractStruct
{
    /**
     * The partiiton index.
     *
     * @var int
     */
    protected $partitionIndex = 0;

    /**
     * The error code, or 0 if there was no fetch error.
     *
     * @var int
     */
    protected $errorCode = 0;

    /**
     * The current high water mark.
     *
     * @var int
     */
    protected $highWatermark = 0;

    /**
     * The last stable offset (or LSO) of the partition. This is the last offset such that the state of all transactional records prior to this offset have been decided (ABORTED or COMMITTED).
     *
     * @var int
     */
    protected $lastStableOffset = -1;

    /**
     * The current log start offset.
     *
     * @var int
     */
    protected $logStartOffset = -1;

    /**
     * The aborted transactions.
     *
     * @var AbortedTransaction[]|null
     */
    protected $aborted = null;

    /**
     * The preferred read replica for the consumer to use on its next fetch request.
     *
     * @var int
     */
    protected $preferredReadReplica = 0;

    /**
     * The record data.
     *
     * @var \longlang\phpkafka\Protocol\RecordBatch\RecordBatch|null
     */
    protected $records = null;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('partitionIndex', 'int32', false, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11], [], [], [], null),
                new ProtocolField('errorCode', 'int16', false, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11], [], [], [], null),
                new ProtocolField('highWatermark', 'int64', false, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11], [], [], [], null),
                new ProtocolField('lastStableOffset', 'int64', false, [4, 5, 6, 7, 8, 9, 10, 11], [], [], [], null),
                new ProtocolField('logStartOffset', 'int64', false, [5, 6, 7, 8, 9, 10, 11], [], [], [], null),
                new ProtocolField('aborted', AbortedTransaction::class, true, [4, 5, 6, 7, 8, 9, 10, 11], [], [4, 5, 6, 7, 8, 9, 10, 11], [], null),
                new ProtocolField('preferredReadReplica', 'int32', false, [11], [], [], [], null),
                new ProtocolField('records', '\longlang\phpkafka\Protocol\RecordBatch\RecordBatch', false, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11], [], [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11], [], null),
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

    public function getErrorCode(): int
    {
        return $this->errorCode;
    }

    public function setErrorCode(int $errorCode): self
    {
        $this->errorCode = $errorCode;

        return $this;
    }

    public function getHighWatermark(): int
    {
        return $this->highWatermark;
    }

    public function setHighWatermark(int $highWatermark): self
    {
        $this->highWatermark = $highWatermark;

        return $this;
    }

    public function getLastStableOffset(): int
    {
        return $this->lastStableOffset;
    }

    public function setLastStableOffset(int $lastStableOffset): self
    {
        $this->lastStableOffset = $lastStableOffset;

        return $this;
    }

    public function getLogStartOffset(): int
    {
        return $this->logStartOffset;
    }

    public function setLogStartOffset(int $logStartOffset): self
    {
        $this->logStartOffset = $logStartOffset;

        return $this;
    }

    /**
     * @return AbortedTransaction[]|null
     */
    public function getAborted(): ?array
    {
        return $this->aborted;
    }

    /**
     * @param AbortedTransaction[]|null $aborted
     */
    public function setAborted(?array $aborted): self
    {
        $this->aborted = $aborted;

        return $this;
    }

    public function getPreferredReadReplica(): int
    {
        return $this->preferredReadReplica;
    }

    public function setPreferredReadReplica(int $preferredReadReplica): self
    {
        $this->preferredReadReplica = $preferredReadReplica;

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
