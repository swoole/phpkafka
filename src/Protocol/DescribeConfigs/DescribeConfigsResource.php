<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\DescribeConfigs;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolField;

class DescribeConfigsResource extends AbstractStruct
{
    /**
     * The resource type.
     *
     * @var int
     */
    protected $resourceType = 0;

    /**
     * The resource name.
     *
     * @var string
     */
    protected $resourceName = '';

    /**
     * The configuration keys to list, or null to list all configuration keys.
     *
     * @var string[]|null
     */
    protected $configurationKeys = null;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('resourceType', 'int8', false, [0, 1, 2, 3], [], [], [], null),
                new ProtocolField('resourceName', 'string', false, [0, 1, 2, 3], [], [], [], null),
                new ProtocolField('configurationKeys', 'string', true, [0, 1, 2, 3], [], [0, 1, 2, 3], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [];
    }

    public function getResourceType(): int
    {
        return $this->resourceType;
    }

    public function setResourceType(int $resourceType): self
    {
        $this->resourceType = $resourceType;

        return $this;
    }

    public function getResourceName(): string
    {
        return $this->resourceName;
    }

    public function setResourceName(string $resourceName): self
    {
        $this->resourceName = $resourceName;

        return $this;
    }

    /**
     * @return string[]|null
     */
    public function getConfigurationKeys(): ?array
    {
        return $this->configurationKeys;
    }

    /**
     * @param string[]|null $configurationKeys
     */
    public function setConfigurationKeys(?array $configurationKeys): self
    {
        $this->configurationKeys = $configurationKeys;

        return $this;
    }
}
