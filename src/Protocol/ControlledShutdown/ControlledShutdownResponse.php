<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\ControlledShutdown;

use longlang\phpkafka\Protocol\AbstractResponse;
use longlang\phpkafka\Protocol\ProtocolField;

class ControlledShutdownResponse extends AbstractResponse
{
    /**
     * The top-level error code.
     *
     * @var int
     */
    protected $errorCode = 0;

    /**
     * The partitions that the broker still leads.
     *
     * @var RemainingPartition[]
     */
    protected $remainingPartitions = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('errorCode', 'int16', false, [0, 1, 2, 3], [3], [], [], null),
                new ProtocolField('remainingPartitions', RemainingPartition::class, true, [0, 1, 2, 3], [3], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 7;
    }

    public function getFlexibleVersions(): array
    {
        return [3];
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
     * @return RemainingPartition[]
     */
    public function getRemainingPartitions(): array
    {
        return $this->remainingPartitions;
    }

    /**
     * @param RemainingPartition[] $remainingPartitions
     */
    public function setRemainingPartitions(array $remainingPartitions): self
    {
        $this->remainingPartitions = $remainingPartitions;

        return $this;
    }
}
