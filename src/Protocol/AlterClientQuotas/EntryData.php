<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\AlterClientQuotas;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolField;

class EntryData extends AbstractStruct
{
    /**
     * The error code, or `0` if the quota alteration succeeded.
     *
     * @var int
     */
    protected $errorCode = 0;

    /**
     * The error message, or `null` if the quota alteration succeeded.
     *
     * @var string|null
     */
    protected $errorMessage = null;

    /**
     * The quota entity to alter.
     *
     * @var EntityData[]
     */
    protected $entity = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('errorCode', 'int16', false, [0], [], [], [], null),
                new ProtocolField('errorMessage', 'string', false, [0], [], [0], [], null),
                new ProtocolField('entity', EntityData::class, true, [0], [], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
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

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    public function setErrorMessage(?string $errorMessage): self
    {
        $this->errorMessage = $errorMessage;

        return $this;
    }

    /**
     * @return EntityData[]
     */
    public function getEntity(): array
    {
        return $this->entity;
    }

    /**
     * @param EntityData[] $entity
     */
    public function setEntity(array $entity): self
    {
        $this->entity = $entity;

        return $this;
    }
}
