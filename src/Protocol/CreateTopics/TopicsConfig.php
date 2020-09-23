<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\CreateTopics;

use Longyan\Kafka\Protocol\AbstractStruct;
use Longyan\Kafka\Protocol\ProtocolField;

class TopicsConfig extends AbstractStruct
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

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('name', 'CompactString', null, 5),
                new ProtocolField('name', 'String16', null, 0),
                new ProtocolField('value', 'CompactNullableString', null, 5),
                new ProtocolField('value', 'NullableString', null, 0),
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
}
