<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\ListOffset;

use Longyan\Kafka\Protocol\AbstractStruct;
use Longyan\Kafka\Protocol\ProtocolField;

class ListOffsetPartition extends AbstractStruct
{
    /**
     * The partition index.
     *
     * @var int
     */
    protected $partitionIndex;

    /**
     * The current leader epoch.
     *
     * @var int
     */
    protected $currentLeaderEpoch;

    /**
     * The current timestamp.
     *
     * @var int
     */
    protected $timestamp;

    /**
     * The maximum number of offsets to report.
     *
     * @var int
     */
    protected $maxNumOffsets;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('partitionIndex', 'int32', false, [0, 1, 2, 3, 4, 5], [], [], [], null),
                new ProtocolField('currentLeaderEpoch', 'int32', false, [4, 5], [], [], [], null),
                new ProtocolField('timestamp', 'int64', false, [0, 1, 2, 3, 4, 5], [], [], [], null),
                new ProtocolField('maxNumOffsets', 'int32', false, [0], [], [], [], null),
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

    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    public function setTimestamp(int $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getMaxNumOffsets(): int
    {
        return $this->maxNumOffsets;
    }

    public function setMaxNumOffsets(int $maxNumOffsets): self
    {
        $this->maxNumOffsets = $maxNumOffsets;

        return $this;
    }
}
