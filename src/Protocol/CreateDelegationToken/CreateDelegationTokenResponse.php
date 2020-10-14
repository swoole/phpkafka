<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\CreateDelegationToken;

use longlang\phpkafka\Protocol\AbstractResponse;
use longlang\phpkafka\Protocol\ProtocolField;

class CreateDelegationTokenResponse extends AbstractResponse
{
    /**
     * The top-level error, or zero if there was no error.
     *
     * @var int
     */
    protected $errorCode = 0;

    /**
     * The principal type of the token owner.
     *
     * @var string
     */
    protected $principalType = '';

    /**
     * The name of the token owner.
     *
     * @var string
     */
    protected $principalName = '';

    /**
     * When this token was generated.
     *
     * @var int
     */
    protected $issueTimestampMs = 0;

    /**
     * When this token expires.
     *
     * @var int
     */
    protected $expiryTimestampMs = 0;

    /**
     * The maximum lifetime of this token.
     *
     * @var int
     */
    protected $maxTimestampMs = 0;

    /**
     * The token UUID.
     *
     * @var string
     */
    protected $tokenId = '';

    /**
     * HMAC of the delegation token.
     *
     * @var string
     */
    protected $hmac = '';

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
                new ProtocolField('principalType', 'string', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('principalName', 'string', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('issueTimestampMs', 'int64', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('expiryTimestampMs', 'int64', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('maxTimestampMs', 'int64', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('tokenId', 'string', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('hmac', 'bytes', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('throttleTimeMs', 'int32', false, [0, 1, 2], [2], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 38;
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

    public function getPrincipalType(): string
    {
        return $this->principalType;
    }

    public function setPrincipalType(string $principalType): self
    {
        $this->principalType = $principalType;

        return $this;
    }

    public function getPrincipalName(): string
    {
        return $this->principalName;
    }

    public function setPrincipalName(string $principalName): self
    {
        $this->principalName = $principalName;

        return $this;
    }

    public function getIssueTimestampMs(): int
    {
        return $this->issueTimestampMs;
    }

    public function setIssueTimestampMs(int $issueTimestampMs): self
    {
        $this->issueTimestampMs = $issueTimestampMs;

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

    public function getMaxTimestampMs(): int
    {
        return $this->maxTimestampMs;
    }

    public function setMaxTimestampMs(int $maxTimestampMs): self
    {
        $this->maxTimestampMs = $maxTimestampMs;

        return $this;
    }

    public function getTokenId(): string
    {
        return $this->tokenId;
    }

    public function setTokenId(string $tokenId): self
    {
        $this->tokenId = $tokenId;

        return $this;
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
