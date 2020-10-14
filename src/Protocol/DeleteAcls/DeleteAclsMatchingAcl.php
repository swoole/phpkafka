<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\DeleteAcls;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolField;

class DeleteAclsMatchingAcl extends AbstractStruct
{
    /**
     * The deletion error code, or 0 if the deletion succeeded.
     *
     * @var int
     */
    protected $errorCode = 0;

    /**
     * The deletion error message, or null if the deletion succeeded.
     *
     * @var string|null
     */
    protected $errorMessage = null;

    /**
     * The ACL resource type.
     *
     * @var int
     */
    protected $resourceType = 0;

    /**
     * The ACL resource name.
     *
     * @var string
     */
    protected $resourceName = '';

    /**
     * The ACL resource pattern type.
     *
     * @var int
     */
    protected $patternType = 3;

    /**
     * The ACL principal.
     *
     * @var string
     */
    protected $principal = '';

    /**
     * The ACL host.
     *
     * @var string
     */
    protected $host = '';

    /**
     * The ACL operation.
     *
     * @var int
     */
    protected $operation = 0;

    /**
     * The ACL permission type.
     *
     * @var int
     */
    protected $permissionType = 0;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('errorCode', 'int16', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('errorMessage', 'string', false, [0, 1, 2], [2], [0, 1, 2], [], null),
                new ProtocolField('resourceType', 'int8', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('resourceName', 'string', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('patternType', 'int8', false, [1, 2], [2], [], [], null),
                new ProtocolField('principal', 'string', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('host', 'string', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('operation', 'int8', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('permissionType', 'int8', false, [0, 1, 2], [2], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [2];
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

    public function getPatternType(): int
    {
        return $this->patternType;
    }

    public function setPatternType(int $patternType): self
    {
        $this->patternType = $patternType;

        return $this;
    }

    public function getPrincipal(): string
    {
        return $this->principal;
    }

    public function setPrincipal(string $principal): self
    {
        $this->principal = $principal;

        return $this;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function setHost(string $host): self
    {
        $this->host = $host;

        return $this;
    }

    public function getOperation(): int
    {
        return $this->operation;
    }

    public function setOperation(int $operation): self
    {
        $this->operation = $operation;

        return $this;
    }

    public function getPermissionType(): int
    {
        return $this->permissionType;
    }

    public function setPermissionType(int $permissionType): self
    {
        $this->permissionType = $permissionType;

        return $this;
    }
}
