<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol;

class ProtocolField
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string|null
     */
    private $arrayType;

    /**
     * @var int
     */
    private $version;

    public function __construct(string $name, string $type, ?string $arrayType, int $version)
    {
        $this->name = $name;
        $this->type = $type;
        $this->arrayType = $arrayType;
        $this->version = $version;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getArrayType(): ?string
    {
        return $this->arrayType;
    }

    public function getVersion(): int
    {
        return $this->version;
    }

    public function getTypeForDisplay(): string
    {
        if (null === $this->arrayType) {
            return $this->type;
        } else {
            return $this->arrayType . '<' . $this->type . '>';
        }
    }
}
