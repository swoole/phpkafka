<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\CreateTopics;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolField;

class CreatableTopicConfigs extends AbstractStruct
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

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('name', 'string', false, [5], [5], [], [], null),
                new ProtocolField('value', 'string', false, [5], [5], [5], [], null),
                new ProtocolField('readOnly', 'bool', false, [5], [5], [], [], null),
                new ProtocolField('configSource', 'int8', false, [5], [5], [], [], null),
                new ProtocolField('isSensitive', 'bool', false, [5], [5], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [5];
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
}
