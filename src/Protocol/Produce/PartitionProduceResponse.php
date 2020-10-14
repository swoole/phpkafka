<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\Produce;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolField;

class PartitionProduceResponse extends AbstractStruct
{
    /**
     * The partition index.
     *
     * @var int
     */
    protected $partitionIndex = 0;

    /**
     * The error code, or 0 if there was no error.
     *
     * @var int
     */
    protected $errorCode = 0;

    /**
     * The base offset.
     *
     * @var int
     */
    protected $baseOffset = 0;

    /**
     * The timestamp returned by broker after appending the messages. If CreateTime is used for the topic, the timestamp will be -1.  If LogAppendTime is used for the topic, the timestamp will be the broker local time when the messages are appended.
     *
     * @var int
     */
    protected $logAppendTimeMs = -1;

    /**
     * The log start offset.
     *
     * @var int
     */
    protected $logStartOffset = -1;

    /**
     * The batch indices of records that caused the batch to be dropped.
     *
     * @var BatchIndexAndErrorMessage[]
     */
    protected $recordErrors = [];

    /**
     * The global error message summarizing the common root cause of the records that caused the batch to be dropped.
     *
     * @var string|null
     */
    protected $errorMessage = null;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('partitionIndex', 'int32', false, [0, 1, 2, 3, 4, 5, 6, 7, 8], [], [], [], null),
                new ProtocolField('errorCode', 'int16', false, [0, 1, 2, 3, 4, 5, 6, 7, 8], [], [], [], null),
                new ProtocolField('baseOffset', 'int64', false, [0, 1, 2, 3, 4, 5, 6, 7, 8], [], [], [], null),
                new ProtocolField('logAppendTimeMs', 'int64', false, [2, 3, 4, 5, 6, 7, 8], [], [], [], null),
                new ProtocolField('logStartOffset', 'int64', false, [5, 6, 7, 8], [], [], [], null),
                new ProtocolField('recordErrors', BatchIndexAndErrorMessage::class, true, [8], [], [], [], null),
                new ProtocolField('errorMessage', 'string', false, [8], [], [8], [], null),
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

    public function getBaseOffset(): int
    {
        return $this->baseOffset;
    }

    public function setBaseOffset(int $baseOffset): self
    {
        $this->baseOffset = $baseOffset;

        return $this;
    }

    public function getLogAppendTimeMs(): int
    {
        return $this->logAppendTimeMs;
    }

    public function setLogAppendTimeMs(int $logAppendTimeMs): self
    {
        $this->logAppendTimeMs = $logAppendTimeMs;

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
     * @return BatchIndexAndErrorMessage[]
     */
    public function getRecordErrors(): array
    {
        return $this->recordErrors;
    }

    /**
     * @param BatchIndexAndErrorMessage[] $recordErrors
     */
    public function setRecordErrors(array $recordErrors): self
    {
        $this->recordErrors = $recordErrors;

        return $this;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    public function setErrorMessage(?string $errorMessage): self
    {
        $this->errorMessage = $errorMessage;

        return $this;
    }
}
