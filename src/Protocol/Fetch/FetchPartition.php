<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\Fetch;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolField;

class FetchPartition extends AbstractStruct
{
    /**
     * The partition index.
     *
     * @var int
     */
    protected $partitionIndex = 0;

    /**
     * The current leader epoch of the partition.
     *
     * @var int
     */
    protected $currentLeaderEpoch = -1;

    /**
     * The message offset.
     *
     * @var int
     */
    protected $fetchOffset = 0;

    /**
     * The earliest available offset of the follower replica.  The field is only used when the request is sent by the follower.
     *
     * @var int
     */
    protected $logStartOffset = -1;

    /**
     * The maximum bytes to fetch from this partition.  See KIP-74 for cases where this limit may not be honored.
     *
     * @var int
     */
    protected $maxBytes = 0;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('partitionIndex', 'int32', false, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11], [], [], [], null),
                new ProtocolField('currentLeaderEpoch', 'int32', false, [9, 10, 11], [], [], [], null),
                new ProtocolField('fetchOffset', 'int64', false, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11], [], [], [], null),
                new ProtocolField('logStartOffset', 'int64', false, [5, 6, 7, 8, 9, 10, 11], [], [], [], null),
                new ProtocolField('maxBytes', 'int32', false, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11], [], [], [], null),
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

    public function getCurrentLeaderEpoch(): int
    {
        return $this->currentLeaderEpoch;
    }

    public function setCurrentLeaderEpoch(int $currentLeaderEpoch): self
    {
        $this->currentLeaderEpoch = $currentLeaderEpoch;

        return $this;
    }

    public function getFetchOffset(): int
    {
        return $this->fetchOffset;
    }

    public function setFetchOffset(int $fetchOffset): self
    {
        $this->fetchOffset = $fetchOffset;

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

    public function getMaxBytes(): int
    {
        return $this->maxBytes;
    }

    public function setMaxBytes(int $maxBytes): self
    {
        $this->maxBytes = $maxBytes;

        return $this;
    }
}
