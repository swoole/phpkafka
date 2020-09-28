<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\AlterPartitionReassignments;

use Longyan\Kafka\Protocol\AbstractStruct;
use Longyan\Kafka\Protocol\ProtocolField;

class ReassignablePartitionResponse extends AbstractStruct
{
    /**
     * The partition index.
     *
     * @var int
     */
    protected $partitionIndex;

    /**
     * The error code for this partition, or 0 if there was no error.
     *
     * @var int
     */
    protected $errorCode;

    /**
     * The error message for this partition, or null if there was no error.
     *
     * @var string|null
     */
    protected $errorMessage;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('partitionIndex', 'int32', false, [0], [0], [], [], null),
                new ProtocolField('errorCode', 'int16', false, [0], [0], [], [], null),
                new ProtocolField('errorMessage', 'string', false, [0], [0], [0], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [0];
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
