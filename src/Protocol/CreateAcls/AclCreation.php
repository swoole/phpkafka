<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\CreateAcls;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolField;

class AclCreation extends AbstractStruct
{
    /**
     * The type of the resource.
     *
     * @var int
     */
    protected $resourceType = 0;

    /**
     * The resource name for the ACL.
     *
     * @var string
     */
    protected $resourceName = '';

    /**
     * The pattern type for the ACL.
     *
     * @var int
     */
    protected $resourcePatternType = 3;

    /**
     * The principal for the ACL.
     *
     * @var string
     */
    protected $principal = '';

    /**
     * The host for the ACL.
     *
     * @var string
     */
    protected $host = '';

    /**
     * The operation type for the ACL (read, write, etc.).
     *
     * @var int
     */
    protected $operation = 0;

    /**
     * The permission type for the ACL (allow, deny, etc.).
     *
     * @var int
     */
    protected $permissionType = 0;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('resourceType', 'int8', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('resourceName', 'string', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('resourcePatternType', 'int8', false, [1, 2], [2], [], [], null),
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

    public function getResourcePatternType(): int
    {
        return $this->resourcePatternType;
    }

    public function setResourcePatternType(int $resourcePatternType): self
    {
        $this->resourcePatternType = $resourcePatternType;

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
