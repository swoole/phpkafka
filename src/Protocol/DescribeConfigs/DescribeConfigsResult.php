<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\DescribeConfigs;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolField;

class DescribeConfigsResult extends AbstractStruct
{
    /**
     * The error code, or 0 if we were able to successfully describe the configurations.
     *
     * @var int
     */
    protected $errorCode = 0;

    /**
     * The error message, or null if we were able to successfully describe the configurations.
     *
     * @var string|null
     */
    protected $errorMessage = null;

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
     * Each listed configuration.
     *
     * @var DescribeConfigsResourceResult[]
     */
    protected $configs = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('errorCode', 'int16', false, [0, 1, 2, 3], [], [], [], null),
                new ProtocolField('errorMessage', 'string', false, [0, 1, 2, 3], [], [0, 1, 2, 3], [], null),
                new ProtocolField('resourceType', 'int8', false, [0, 1, 2, 3], [], [], [], null),
                new ProtocolField('resourceName', 'string', false, [0, 1, 2, 3], [], [], [], null),
                new ProtocolField('configs', DescribeConfigsResourceResult::class, true, [0, 1, 2, 3], [], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [];
    }

    public function getErrorCode(): int
    {
        return $this->errorCode;
    }

    public function setErrorCode(int $errorCode): self
    {
        $this->errorCode = $errorCode;

        return $this;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    public function setErrorMessage(?string $errorMessage): self
    {
        $this->errorMessage = $errorMessage;

        return $this;
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
     * @return DescribeConfigsResourceResult[]
     */
    public function getConfigs(): array
    {
        return $this->configs;
    }

    /**
     * @param DescribeConfigsResourceResult[] $configs
     */
    public function setConfigs(array $configs): self
    {
        $this->configs = $configs;

        return $this;
    }
}
