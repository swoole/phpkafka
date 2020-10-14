<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\AlterConfigs;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolField;

class AlterableConfig extends AbstractStruct
{
    /**
     * The configuration key name.
     *
     * @var string
     */
    protected $name = '';

    /**
     * The value to set for the configuration key.
     *
     * @var string|null
     */
    protected $value = null;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('name', 'string', false, [0, 1], [], [], [], null),
                new ProtocolField('value', 'string', false, [0, 1], [], [0, 1], [], null),
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
}
