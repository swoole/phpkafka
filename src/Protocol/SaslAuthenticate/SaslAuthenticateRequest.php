<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\SaslAuthenticate;

use longlang\phpkafka\Protocol\AbstractRequest;
use longlang\phpkafka\Protocol\ProtocolField;

class SaslAuthenticateRequest extends AbstractRequest
{
    /**
     * The SASL authentication bytes from the client, as defined by the SASL mechanism.
     *
     * @var string
     */
    protected $authBytes = '';

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('authBytes', 'bytes', false, [0, 1, 2], [2], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 36;
    }

    public function getMaxSupportedVersion(): int
    {
        return 2;
    }

    public function getFlexibleVersions(): array
    {
        return [2];
    }

    public function getAuthBytes(): string
    {
        return $this->authBytes;
    }

    public function setAuthBytes(string $authBytes): self
    {
        $this->authBytes = $authBytes;

        return $this;
    }
}
