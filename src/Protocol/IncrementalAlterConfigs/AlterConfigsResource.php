<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\IncrementalAlterConfigs;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolField;

class AlterConfigsResource extends AbstractStruct
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
     * The configurations.
     *
     * @var AlterableConfig[]
     */
    protected $configs = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('resourceType', 'int8', false, [0, 1], [1], [], [], null),
                new ProtocolField('resourceName', 'string', false, [0, 1], [1], [], [], null),
                new ProtocolField('configs', AlterableConfig::class, true, [0, 1], [1], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [1];
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
     * @return AlterableConfig[]
     */
    public function getConfigs(): array
    {
        return $this->configs;
    }

    /**
     * @param AlterableConfig[] $configs
     */
    public function setConfigs(array $configs): self
    {
        $this->configs = $configs;

        return $this;
    }
}
