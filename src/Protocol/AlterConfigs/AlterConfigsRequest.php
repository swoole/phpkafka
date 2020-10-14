<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\AlterConfigs;

use longlang\phpkafka\Protocol\AbstractRequest;
use longlang\phpkafka\Protocol\ProtocolField;

class AlterConfigsRequest extends AbstractRequest
{
    /**
     * The updates for each resource.
     *
     * @var AlterConfigsResource[]
     */
    protected $resources = [];

    /**
     * True if we should validate the request, but not change the configurations.
     *
     * @var bool
     */
    protected $validateOnly = false;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('resources', AlterConfigsResource::class, true, [0, 1], [], [], [], null),
                new ProtocolField('validateOnly', 'bool', false, [0, 1], [], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 33;
    }

    public function getMaxSupportedVersion(): int
    {
        return 1;
    }

    public function getFlexibleVersions(): array
    {
        return [];
    }

    /**
     * @return AlterConfigsResource[]
     */
    public function getResources(): array
    {
        return $this->resources;
    }

    /**
     * @param AlterConfigsResource[] $resources
     */
    public function setResources(array $resources): self
    {
        $this->resources = $resources;

        return $this;
    }

    public function getValidateOnly(): bool
    {
        return $this->validateOnly;
    }

    public function setValidateOnly(bool $validateOnly): self
    {
        $this->validateOnly = $validateOnly;

        return $this;
    }
}
