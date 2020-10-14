<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\DescribeClientQuotas;

use longlang\phpkafka\Protocol\AbstractRequest;
use longlang\phpkafka\Protocol\ProtocolField;

class DescribeClientQuotasRequest extends AbstractRequest
{
    /**
     * Filter components to apply to quota entities.
     *
     * @var ComponentData[]
     */
    protected $components = [];

    /**
     * Whether the match is strict, i.e. should exclude entities with unspecified entity types.
     *
     * @var bool
     */
    protected $strict = false;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('components', ComponentData::class, true, [0], [], [], [], null),
                new ProtocolField('strict', 'bool', false, [0], [], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 48;
    }

    public function getMaxSupportedVersion(): int
    {
        return 0;
    }

    public function getFlexibleVersions(): array
    {
        return [];
    }

    /**
     * @return ComponentData[]
     */
    public function getComponents(): array
    {
        return $this->components;
    }

    /**
     * @param ComponentData[] $components
     */
    public function setComponents(array $components): self
    {
        $this->components = $components;

        return $this;
    }

    public function getStrict(): bool
    {
        return $this->strict;
    }

    public function setStrict(bool $strict): self
    {
        $this->strict = $strict;

        return $this;
    }
}
