<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\LeaderAndIsr;

use longlang\phpkafka\Protocol\AbstractResponse;
use longlang\phpkafka\Protocol\ProtocolField;

class LeaderAndIsrResponse extends AbstractResponse
{
    /**
     * The error code, or 0 if there was no error.
     *
     * @var int
     */
    protected $errorCode = 0;

    /**
     * Each partition.
     *
     * @var LeaderAndIsrPartitionError[]
     */
    protected $partitionErrors = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('errorCode', 'int16', false, [0, 1, 2, 3, 4], [4], [], [], null),
                new ProtocolField('partitionErrors', LeaderAndIsrPartitionError::class, true, [0, 1, 2, 3, 4], [4], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 4;
    }

    public function getFlexibleVersions(): array
    {
        return [4];
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
     * @return LeaderAndIsrPartitionError[]
     */
    public function getPartitionErrors(): array
    {
        return $this->partitionErrors;
    }

    /**
     * @param LeaderAndIsrPartitionError[] $partitionErrors
     */
    public function setPartitionErrors(array $partitionErrors): self
    {
        $this->partitionErrors = $partitionErrors;

        return $this;
    }
}
