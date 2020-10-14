<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\DeleteRecords;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolField;

class DeleteRecordsPartitionResult extends AbstractStruct
{
    /**
     * The partition index.
     *
     * @var int
     */
    protected $partitionIndex = 0;

    /**
     * The partition low water mark.
     *
     * @var int
     */
    protected $lowWatermark = 0;

    /**
     * The deletion error code, or 0 if the deletion succeeded.
     *
     * @var int
     */
    protected $errorCode = 0;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('partitionIndex', 'int32', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('lowWatermark', 'int64', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('errorCode', 'int16', false, [0, 1, 2], [2], [], [], null),
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

    public function getLowWatermark(): int
    {
        return $this->lowWatermark;
    }

    public function setLowWatermark(int $lowWatermark): self
    {
        $this->lowWatermark = $lowWatermark;

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
