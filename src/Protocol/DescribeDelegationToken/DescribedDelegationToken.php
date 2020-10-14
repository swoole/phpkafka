<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\DescribeDelegationToken;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolField;

class DescribedDelegationToken extends AbstractStruct
{
    /**
     * The token principal type.
     *
     * @var string
     */
    protected $principalType = '';

    /**
     * The token principal name.
     *
     * @var string
     */
    protected $principalName = '';

    /**
     * The token issue timestamp in milliseconds.
     *
     * @var int
     */
    protected $issueTimestamp = 0;

    /**
     * The token expiry timestamp in milliseconds.
     *
     * @var int
     */
    protected $expiryTimestamp = 0;

    /**
     * The token maximum timestamp length in milliseconds.
     *
     * @var int
     */
    protected $maxTimestamp = 0;

    /**
     * The token ID.
     *
     * @var string
     */
    protected $tokenId = '';

    /**
     * The token HMAC.
     *
     * @var string
     */
    protected $hmac = '';

    /**
     * Those who are able to renew this token before it expires.
     *
     * @var DescribedDelegationTokenRenewer[]
     */
    protected $renewers = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('principalType', 'string', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('principalName', 'string', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('issueTimestamp', 'int64', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('expiryTimestamp', 'int64', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('maxTimestamp', 'int64', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('tokenId', 'string', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('hmac', 'bytes', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('renewers', DescribedDelegationTokenRenewer::class, true, [0, 1, 2], [2], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [2];
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

    public function getIssueTimestamp(): int
    {
        return $this->issueTimestamp;
    }

    public function setIssueTimestamp(int $issueTimestamp): self
    {
        $this->issueTimestamp = $issueTimestamp;

        return $this;
    }

    public function getExpiryTimestamp(): int
    {
        return $this->expiryTimestamp;
    }

    public function setExpiryTimestamp(int $expiryTimestamp): self
    {
        $this->expiryTimestamp = $expiryTimestamp;

        return $this;
    }

    public function getMaxTimestamp(): int
    {
        return $this->maxTimestamp;
    }

    public function setMaxTimestamp(int $maxTimestamp): self
    {
        $this->maxTimestamp = $maxTimestamp;

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

    /**
     * @return DescribedDelegationTokenRenewer[]
     */
    public function getRenewers(): array
    {
        return $this->renewers;
    }

    /**
     * @param DescribedDelegationTokenRenewer[] $renewers
     */
    public function setRenewers(array $renewers): self
    {
        $this->renewers = $renewers;

        return $this;
    }
}
