<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\DescribeDelegationToken;

use longlang\phpkafka\Protocol\AbstractResponse;
use longlang\phpkafka\Protocol\ProtocolField;

class DescribeDelegationTokenResponse extends AbstractResponse
{
    /**
     * The error code, or 0 if there was no error.
     *
     * @var int
     */
    protected $errorCode = 0;

    /**
     * The tokens.
     *
     * @var DescribedDelegationToken[]
     */
    protected $tokens = [];

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
                new ProtocolField('tokens', DescribedDelegationToken::class, true, [0, 1, 2], [2], [], [], null),
                new ProtocolField('throttleTimeMs', 'int32', false, [0, 1, 2], [2], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 41;
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

    /**
     * @return DescribedDelegationToken[]
     */
    public function getTokens(): array
    {
        return $this->tokens;
    }

    /**
     * @param DescribedDelegationToken[] $tokens
     */
    public function setTokens(array $tokens): self
    {
        $this->tokens = $tokens;

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
