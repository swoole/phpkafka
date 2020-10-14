<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\DescribeDelegationToken;

use longlang\phpkafka\Protocol\AbstractRequest;
use longlang\phpkafka\Protocol\ProtocolField;

class DescribeDelegationTokenRequest extends AbstractRequest
{
    /**
     * Each owner that we want to describe delegation tokens for, or null to describe all tokens.
     *
     * @var DescribeDelegationTokenOwner[]|null
     */
    protected $owners = null;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('owners', DescribeDelegationTokenOwner::class, true, [0, 1, 2], [2], [0, 1, 2], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 41;
    }

    public function getMaxSupportedVersion(): int
    {
        return 2;
    }

    public function getFlexibleVersions(): array
    {
        return [2];
    }

    /**
     * @return DescribeDelegationTokenOwner[]|null
     */
    public function getOwners(): ?array
    {
        return $this->owners;
    }

    /**
     * @param DescribeDelegationTokenOwner[]|null $owners
     */
    public function setOwners(?array $owners): self
    {
        $this->owners = $owners;

        return $this;
    }
}
