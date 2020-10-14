<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\ExpireDelegationToken;

use longlang\phpkafka\Protocol\AbstractRequest;
use longlang\phpkafka\Protocol\ProtocolField;

class ExpireDelegationTokenRequest extends AbstractRequest
{
    /**
     * The HMAC of the delegation token to be expired.
     *
     * @var string
     */
    protected $hmac = '';

    /**
     * The expiry time period in milliseconds.
     *
     * @var int
     */
    protected $expiryTimePeriodMs = 0;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('hmac', 'bytes', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('expiryTimePeriodMs', 'int64', false, [0, 1, 2], [2], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 40;
    }

    public function getMaxSupportedVersion(): int
    {
        return 2;
    }

    public function getFlexibleVersions(): array
    {
        return [2];
    }

    public function getHmac(): string
    {
        return $this->hmac;
    }

    public function setHmac(string $hmac): self
    {
        $this->hmac = $hmac;

        return $this;
    }

    public function getExpiryTimePeriodMs(): int
    {
        return $this->expiryTimePeriodMs;
    }

    public function setExpiryTimePeriodMs(int $expiryTimePeriodMs): self
    {
        $this->expiryTimePeriodMs = $expiryTimePeriodMs;

        return $this;
    }
}
