<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\InitProducerId;

use longlang\phpkafka\Protocol\AbstractResponse;
use longlang\phpkafka\Protocol\ProtocolField;

class InitProducerIdResponse extends AbstractResponse
{
    /**
     * The duration in milliseconds for which the request was throttled due to a quota violation, or zero if the request did not violate any quota.
     *
     * @var int
     */
    protected $throttleTimeMs = 0;

    /**
     * The error code, or 0 if there was no error.
     *
     * @var int
     */
    protected $errorCode = 0;

    /**
     * The current producer id.
     *
     * @var int
     */
    protected $producerId = -1;

    /**
     * The current epoch associated with the producer id.
     *
     * @var int
     */
    protected $producerEpoch = 0;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('throttleTimeMs', 'int32', false, [0, 1, 2, 3], [2, 3], [], [], null),
                new ProtocolField('errorCode', 'int16', false, [0, 1, 2, 3], [2, 3], [], [], null),
                new ProtocolField('producerId', 'int64', false, [0, 1, 2, 3], [2, 3], [], [], null),
                new ProtocolField('producerEpoch', 'int16', false, [0, 1, 2, 3], [2, 3], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 22;
    }

    public function getFlexibleVersions(): array
    {
        return [2, 3];
    }

    public function getThrottleTimeMs(): int
    {
        return $this->throttleTimeMs;
    }

    public function setThrottleTimeMs(int $throttleTimeMs): self
    {
        $this->throttleTimeMs = $throttleTimeMs;

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

    public function getProducerId(): int
    {
        return $this->producerId;
    }

    public function setProducerId(int $producerId): self
    {
        $this->producerId = $producerId;

        return $this;
    }

    public function getProducerEpoch(): int
    {
        return $this->producerEpoch;
    }

    public function setProducerEpoch(int $producerEpoch): self
    {
        $this->producerEpoch = $producerEpoch;

        return $this;
    }
}
