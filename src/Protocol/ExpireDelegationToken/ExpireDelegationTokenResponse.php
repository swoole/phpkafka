<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\ExpireDelegationToken;

use longlang\phpkafka\Protocol\AbstractResponse;
use longlang\phpkafka\Protocol\ProtocolField;

class ExpireDelegationTokenResponse extends AbstractResponse
{
    /**
     * The error code, or 0 if there was no error.
     *
     * @var int
     */
    protected $errorCode = 0;

    /**
     * The timestamp in milliseconds at which this token expires.
     *
     * @var int
     */
    protected $expiryTimestampMs = 0;

    /**
     * The duration in milliseconds for which the request was throttled due to a quota violation, or zero if the request did not violate any quota.
     *
     * @var int
     */
    protected $throttleTimeMs = 0;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('errorCode', 'int16', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('expiryTimestampMs', 'int64', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('throttleTimeMs', 'int32', false, [0, 1, 2], [2], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 40;
    }

    public function getFlexibleVersions(): array
    {
        return [2];
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

    public function getExpiryTimestampMs(): int
    {
        return $this->expiryTimestampMs;
    }

    public function setExpiryTimestampMs(int $expiryTimestampMs): self
    {
        $this->expiryTimestampMs = $expiryTimestampMs;

        return $this;
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
}
