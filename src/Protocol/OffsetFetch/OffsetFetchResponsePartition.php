<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\OffsetFetch;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolField;

class OffsetFetchResponsePartition extends AbstractStruct
{
    /**
     * The partition index.
     *
     * @var int
     */
    protected $partitionIndex = 0;

    /**
     * The committed message offset.
     *
     * @var int
     */
    protected $committedOffset = 0;

    /**
     * The leader epoch.
     *
     * @var int
     */
    protected $committedLeaderEpoch = -1;

    /**
     * The partition metadata.
     *
     * @var string|null
     */
    protected $metadata = null;

    /**
     * The error code, or 0 if there was no error.
     *
     * @var int
     */
    protected $errorCode = 0;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('partitionIndex', 'int32', false, [0, 1, 2, 3, 4, 5, 6, 7], [6, 7], [], [], null),
                new ProtocolField('committedOffset', 'int64', false, [0, 1, 2, 3, 4, 5, 6, 7], [6, 7], [], [], null),
                new ProtocolField('committedLeaderEpoch', 'int32', false, [5, 6, 7], [6, 7], [], [], null),
                new ProtocolField('metadata', 'string', false, [0, 1, 2, 3, 4, 5, 6, 7], [6, 7], [0, 1, 2, 3, 4, 5, 6, 7], [], null),
                new ProtocolField('errorCode', 'int16', false, [0, 1, 2, 3, 4, 5, 6, 7], [6, 7], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [6, 7];
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

    public function getMetadata(): ?string
    {
        return $this->metadata;
    }

    public function setMetadata(?string $metadata): self
    {
        $this->metadata = $metadata;

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
}
