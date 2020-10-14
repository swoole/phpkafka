<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\ListOffset;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolField;

class ListOffsetPartitionResponse extends AbstractStruct
{
    /**
     * The partition index.
     *
     * @var int
     */
    protected $partitionIndex = 0;

    /**
     * The partition error code, or 0 if there was no error.
     *
     * @var int
     */
    protected $errorCode = 0;

    /**
     * The result offsets.
     *
     * @var int[]
     */
    protected $oldStyleOffsets = [];

    /**
     * The timestamp associated with the returned offset.
     *
     * @var int
     */
    protected $timestamp = -1;

    /**
     * The returned offset.
     *
     * @var int
     */
    protected $offset = -1;

    /**
     * @var int
     */
    protected $leaderEpoch = 0;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('partitionIndex', 'int32', false, [0, 1, 2, 3, 4, 5], [], [], [], null),
                new ProtocolField('errorCode', 'int16', false, [0, 1, 2, 3, 4, 5], [], [], [], null),
                new ProtocolField('oldStyleOffsets', 'int64', true, [0], [], [], [], null),
                new ProtocolField('timestamp', 'int64', false, [1, 2, 3, 4, 5], [], [], [], null),
                new ProtocolField('offset', 'int64', false, [1, 2, 3, 4, 5], [], [], [], null),
                new ProtocolField('leaderEpoch', 'int32', false, [4, 5], [], [], [], null),
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

    /**
     * @return int[]
     */
    public function getOldStyleOffsets(): array
    {
        return $this->oldStyleOffsets;
    }

    /**
     * @param int[] $oldStyleOffsets
     */
    public function setOldStyleOffsets(array $oldStyleOffsets): self
    {
        $this->oldStyleOffsets = $oldStyleOffsets;

        return $this;
    }

    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    public function setTimestamp(int $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function setOffset(int $offset): self
    {
        $this->offset = $offset;

        return $this;
    }

    public function getLeaderEpoch(): int
    {
        return $this->leaderEpoch;
    }

    public function setLeaderEpoch(int $leaderEpoch): self
    {
        $this->leaderEpoch = $leaderEpoch;

        return $this;
    }
}
