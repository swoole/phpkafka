<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol;

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
     * @var bool
     */
    private $isArray;

    /**
     * @var int[]
     */
    private $versions;

    /**
     * @var int[]
     */
    private $flexibleVersions;

    /**
     * @var int[]
     */
    private $nullableVersions;

    /**
     * @var int[]
     */
    private $taggedVersions;

    /**
     * @var int|null
     */
    private $tag;

    public function __construct(string $name, string $type, bool $isArray, array $versions, array $flexibleVersions, array $nullableVersions, array $taggedVersions, ?int $tag = null)
    {
        $this->name = $name;
        $this->type = $type;
        $this->isArray = $isArray;
        $this->versions = $versions;
        $this->flexibleVersions = $flexibleVersions;
        $this->nullableVersions = $nullableVersions;
        $this->taggedVersions = $taggedVersions;
        $this->tag = $tag;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getIsArray(): bool
    {
        return $this->isArray;
    }

    /**
     * @return int[]
     */
    public function getVersions(): array
    {
        return $this->versions;
    }

    /**
     * @return int[]
     */
    public function getFlexibleVersions(): array
    {
        return $this->flexibleVersions;
    }

    /**
     * @return int[]
     */
    public function getNullableVersions(): array
    {
        return $this->nullableVersions;
    }

    /**
     * @return int[]
     */
    public function getTaggedVersions(): array
    {
        return $this->taggedVersions;
    }

    public function getTag(): ?int
    {
        return $this->tag;
    }

    public function getTypeForDisplay(): string
    {
        if ($this->isArray) {
            return $this->type . '[]';
        } else {
            return $this->type;
        }
    }
}
