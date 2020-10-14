<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\SaslHandshake;

use longlang\phpkafka\Protocol\AbstractResponse;
use longlang\phpkafka\Protocol\ProtocolField;

class SaslHandshakeResponse extends AbstractResponse
{
    /**
     * The error code, or 0 if there was no error.
     *
     * @var int
     */
    protected $errorCode = 0;

    /**
     * The mechanisms enabled in the server.
     *
     * @var string[]
     */
    protected $mechanisms = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('errorCode', 'int16', false, [0, 1], [], [], [], null),
                new ProtocolField('mechanisms', 'string', true, [0, 1], [], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 17;
    }

    public function getFlexibleVersions(): array
    {
        return [];
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
     * @return string[]
     */
    public function getMechanisms(): array
    {
        return $this->mechanisms;
    }

    /**
     * @param string[] $mechanisms
     */
    public function setMechanisms(array $mechanisms): self
    {
        $this->mechanisms = $mechanisms;

        return $this;
    }
}
