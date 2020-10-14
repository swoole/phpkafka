<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\ElectLeaders;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolField;

class PartitionResult extends AbstractStruct
{
    /**
     * The partition id.
     *
     * @var int
     */
    protected $partitionId = 0;

    /**
     * The result error, or zero if there was no error.
     *
     * @var int
     */
    protected $errorCode = 0;

    /**
     * The result message, or null if there was no error.
     *
     * @var string|null
     */
    protected $errorMessage = null;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('partitionId', 'int32', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('errorCode', 'int16', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('errorMessage', 'string', false, [0, 1, 2], [2], [0, 1, 2], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [2];
    }

    public function getPartitionId(): int
    {
        return $this->partitionId;
    }

    public function setPartitionId(int $partitionId): self
    {
        $this->partitionId = $partitionId;

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
