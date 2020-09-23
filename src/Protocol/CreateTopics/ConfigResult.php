<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\CreateTopics;

use Longyan\Kafka\Protocol\AbstractStruct;
use Longyan\Kafka\Protocol\ProtocolField;

class ConfigResult extends AbstractStruct
{
    /**
     * The configuration name.
     *
     * @var string
     */
    protected $name;

    /**
     * The configuration value.
     *
     * @var string|null
     */
    protected $value;

    /**
     * True if the configuration is read-only.
     *
     * @var bool
     */
    protected $readonly;

    /**
     * The configuration source.
     *
     * @var int
     */
    protected $configSource;

    /**
     * True if this configuration is sensitive.
     *
     * @var bool
     */
    protected $isSensitive;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('name', 'CompactString', null, 5),
                new ProtocolField('value', 'CompactNullableString', null, 5),
                new ProtocolField('readonly', 'Boolean', null, 5),
                new ProtocolField('configSource', 'Int8', null, 5),
                new ProtocolField('isSensitive', 'Boolean', null, 5),
            ];
            self::$taggedFieldses[self::class] = [];
        }
    }

    public function getFlexibleVersions(): ?int
    {
        return 5;
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

    public function getReadonly(): bool
    {
        return $this->readonly;
    }

    public function setReadonly(bool $readonly): self
    {
        $this->readonly = $readonly;

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
