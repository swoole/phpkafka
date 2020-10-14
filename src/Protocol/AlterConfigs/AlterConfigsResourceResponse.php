<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\AlterConfigs;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolField;

class AlterConfigsResourceResponse extends AbstractStruct
{
    /**
     * The resource error code.
     *
     * @var int
     */
    protected $errorCode = 0;

    /**
     * The resource error message, or null if there was no error.
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

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('errorCode', 'int16', false, [0, 1], [], [], [], null),
                new ProtocolField('errorMessage', 'string', false, [0, 1], [], [0, 1], [], null),
                new ProtocolField('resourceType', 'int8', false, [0, 1], [], [], [], null),
                new ProtocolField('resourceName', 'string', false, [0, 1], [], [], [], null),
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
}
