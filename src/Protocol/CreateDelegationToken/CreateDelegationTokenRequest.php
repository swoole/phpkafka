<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\CreateDelegationToken;

use longlang\phpkafka\Protocol\AbstractRequest;
use longlang\phpkafka\Protocol\ProtocolField;

class CreateDelegationTokenRequest extends AbstractRequest
{
    /**
     * A list of those who are allowed to renew this token before it expires.
     *
     * @var CreatableRenewers[]
     */
    protected $renewers = [];

    /**
     * The maximum lifetime of the token in milliseconds, or -1 to use the server side default.
     *
     * @var int
     */
    protected $maxLifetimeMs = 0;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('renewers', CreatableRenewers::class, true, [0, 1, 2], [2], [], [], null),
                new ProtocolField('maxLifetimeMs', 'int64', false, [0, 1, 2], [2], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 38;
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
     * @return CreatableRenewers[]
     */
    public function getRenewers(): array
    {
        return $this->renewers;
    }

    /**
     * @param CreatableRenewers[] $renewers
     */
    public function setRenewers(array $renewers): self
    {
        $this->renewers = $renewers;

        return $this;
    }

    public function getMaxLifetimeMs(): int
    {
        return $this->maxLifetimeMs;
    }

    public function setMaxLifetimeMs(int $maxLifetimeMs): self
    {
        $this->maxLifetimeMs = $maxLifetimeMs;

        return $this;
    }
}
