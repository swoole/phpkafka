<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\StopReplica;

use longlang\phpkafka\Protocol\AbstractResponse;
use longlang\phpkafka\Protocol\ProtocolField;

class StopReplicaResponse extends AbstractResponse
{
    /**
     * The top-level error code, or 0 if there was no top-level error.
     *
     * @var int
     */
    protected $errorCode = 0;

    /**
     * The responses for each partition.
     *
     * @var StopReplicaPartitionError[]
     */
    protected $partitionErrors = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('errorCode', 'int16', false, [0, 1, 2, 3], [2, 3], [], [], null),
                new ProtocolField('partitionErrors', StopReplicaPartitionError::class, true, [0, 1, 2, 3], [2, 3], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 5;
    }

    public function getFlexibleVersions(): array
    {
        return [2, 3];
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
     * @return StopReplicaPartitionError[]
     */
    public function getPartitionErrors(): array
    {
        return $this->partitionErrors;
    }

    /**
     * @param StopReplicaPartitionError[] $partitionErrors
     */
    public function setPartitionErrors(array $partitionErrors): self
    {
        $this->partitionErrors = $partitionErrors;

        return $this;
    }
}
