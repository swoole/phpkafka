<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\DescribeConfigs;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolField;

class DescribeConfigsResourceResult extends AbstractStruct
{
    /**
     * The configuration name.
     *
     * @var string
     */
    protected $name = '';

    /**
     * The configuration value.
     *
     * @var string|null
     */
    protected $value = null;

    /**
     * True if the configuration is read-only.
     *
     * @var bool
     */
    protected $readOnly = false;

    /**
     * True if the configuration is not set.
     *
     * @var bool
     */
    protected $isDefault = false;

    /**
     * The configuration source.
     *
     * @var int
     */
    protected $configSource = -1;

    /**
     * True if this configuration is sensitive.
     *
     * @var bool
     */
    protected $isSensitive = false;

    /**
     * The synonyms for this configuration key.
     *
     * @var DescribeConfigsSynonym[]
     */
    protected $synonyms = [];

    /**
     * The configuration data type. Type can be one of the following values - BOOLEAN, STRING, INT, SHORT, LONG, DOUBLE, LIST, CLASS, PASSWORD.
     *
     * @var int
     */
    protected $configType = 0;

    /**
     * The configuration documentation.
     *
     * @var string|null
     */
    protected $documentation = null;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('name', 'string', false, [0, 1, 2, 3], [], [], [], null),
                new ProtocolField('value', 'string', false, [0, 1, 2, 3], [], [0, 1, 2, 3], [], null),
                new ProtocolField('readOnly', 'bool', false, [0, 1, 2, 3], [], [], [], null),
                new ProtocolField('isDefault', 'bool', false, [0], [], [], [], null),
                new ProtocolField('configSource', 'int8', false, [1, 2, 3], [], [], [], null),
                new ProtocolField('isSensitive', 'bool', false, [0, 1, 2, 3], [], [], [], null),
                new ProtocolField('synonyms', DescribeConfigsSynonym::class, true, [1, 2, 3], [], [], [], null),
                new ProtocolField('configType', 'int8', false, [3], [], [], [], null),
                new ProtocolField('documentation', 'string', false, [3], [], [0, 1, 2, 3], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getReadOnly(): bool
    {
        return $this->readOnly;
    }

    public function setReadOnly(bool $readOnly): self
    {
        $this->readOnly = $readOnly;

        return $this;
    }

    public function getIsDefault(): bool
    {
        return $this->isDefault;
    }

    public function setIsDefault(bool $isDefault): self
    {
        $this->isDefault = $isDefault;

        return $this;
    }

    public function getConfigSource(): int
    {
        return $this->configSource;
    }

    public function setConfigSource(int $configSource): self
    {
        $this->configSource = $configSource;

        return $this;
    }

    public function getIsSensitive(): bool
    {
        return $this->isSensitive;
    }

    public function setIsSensitive(bool $isSensitive): self
    {
        $this->isSensitive = $isSensitive;

        return $this;
    }

    /**
     * @return DescribeConfigsSynonym[]
     */
    public function getSynonyms(): array
    {
        return $this->synonyms;
    }

    /**
     * @param DescribeConfigsSynonym[] $synonyms
     */
    public function setSynonyms(array $synonyms): self
    {
        $this->synonyms = $synonyms;

        return $this;
    }

    public function getConfigType(): int
    {
        return $this->configType;
    }

    public function setConfigType(int $configType): self
    {
        $this->configType = $configType;

        return $this;
    }

    public function getDocumentation(): ?string
    {
        return $this->documentation;
    }

    public function setDocumentation(?string $documentation): self
    {
        $this->documentation = $documentation;

        return $this;
    }
}
