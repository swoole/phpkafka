<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\TxnOffsetCommit;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolField;

class TxnOffsetCommitRequestPartition extends AbstractStruct
{
    /**
     * The index of the partition within the topic.
     *
     * @var int
     */
    protected $partitionIndex = 0;

    /**
     * The message offset to be committed.
     *
     * @var int
     */
    protected $committedOffset = 0;

    /**
     * The leader epoch of the last consumed record.
     *
     * @var int
     */
    protected $committedLeaderEpoch = -1;

    /**
     * Any associated metadata the client wants to keep.
     *
     * @var string|null
     */
    protected $committedMetadata = null;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('partitionIndex', 'int32', false, [0, 1, 2, 3], [3], [], [], null),
                new ProtocolField('committedOffset', 'int64', false, [0, 1, 2, 3], [3], [], [], null),
                new ProtocolField('committedLeaderEpoch', 'int32', false, [2, 3], [3], [], [], null),
                new ProtocolField('committedMetadata', 'string', false, [0, 1, 2, 3], [3], [0, 1, 2, 3], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [3];
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

    public function getCommittedOffset(): int
    {
        return $this->committedOffset;
    }

    public function setCommittedOffset(int $committedOffset): self
    {
        $this->committedOffset = $committedOffset;

        return $this;
    }

    public function getCommittedLeaderEpoch(): int
    {
        return $this->committedLeaderEpoch;
    }

    public function setCommittedLeaderEpoch(int $committedLeaderEpoch): self
    {
        $this->committedLeaderEpoch = $committedLeaderEpoch;

        return $this;
    }

    public function getCommittedMetadata(): ?string
    {
        return $this->committedMetadata;
    }

    public function setCommittedMetadata(?string $committedMetadata): self
    {
        $this->committedMetadata = $committedMetadata;

        return $this;
    }
}
