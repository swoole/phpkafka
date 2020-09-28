<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\CreateAcls;

use Longyan\Kafka\Protocol\AbstractStruct;
use Longyan\Kafka\Protocol\ProtocolField;

class AclCreationResult extends AbstractStruct
{
    /**
     * The result error, or zero if there was no error.
     *
     * @var int
     */
    protected $errorCode;

    /**
     * The result message, or null if there was no error.
     *
     * @var string|null
     */
    protected $errorMessage;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('errorCode', 'int16', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('errorMessage', 'string', false, [0, 1, 2], [2], [0, 1, 2], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
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
}
