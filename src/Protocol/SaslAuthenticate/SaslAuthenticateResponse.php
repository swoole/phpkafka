<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\SaslAuthenticate;

use longlang\phpkafka\Protocol\AbstractResponse;
use longlang\phpkafka\Protocol\ProtocolField;

class SaslAuthenticateResponse extends AbstractResponse
{
    /**
     * The error code, or 0 if there was no error.
     *
     * @var int
     */
    protected $errorCode = 0;

    /**
     * The error message, or null if there was no error.
     *
     * @var string|null
     */
    protected $errorMessage = null;

    /**
     * The SASL authentication bytes from the server, as defined by the SASL mechanism.
     *
     * @var string
     */
    protected $authBytes = '';

    /**
     * The SASL authentication bytes from the server, as defined by the SASL mechanism.
     *
     * @var int
     */
    protected $sessionLifetimeMs = 0;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('errorCode', 'int16', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('errorMessage', 'string', false, [0, 1, 2], [2], [0, 1, 2], [], null),
                new ProtocolField('authBytes', 'bytes', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('sessionLifetimeMs', 'int64', false, [1, 2], [2], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 36;
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

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    public function setErrorMessage(?string $errorMessage): self
    {
        $this->errorMessage = $errorMessage;

        return $this;
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

    public function getSessionLifetimeMs(): int
    {
        return $this->sessionLifetimeMs;
    }

    public function setSessionLifetimeMs(int $sessionLifetimeMs): self
    {
        $this->sessionLifetimeMs = $sessionLifetimeMs;

        return $this;
    }
}
