<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\SaslHandshake;

use longlang\phpkafka\Protocol\AbstractRequest;
use longlang\phpkafka\Protocol\ProtocolField;

class SaslHandshakeRequest extends AbstractRequest
{
    /**
     * The SASL mechanism chosen by the client.
     *
     * @var string
     */
    protected $mechanism = '';

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('mechanism', 'string', false, [0, 1], [], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 17;
    }

    public function getMaxSupportedVersion(): int
    {
        return 1;
    }

    public function getFlexibleVersions(): array
    {
        return [];
    }

    public function getMechanism(): string
    {
        return $this->mechanism;
    }

    public function setMechanism(string $mechanism): self
    {
        $this->mechanism = $mechanism;

        return $this;
    }
}
