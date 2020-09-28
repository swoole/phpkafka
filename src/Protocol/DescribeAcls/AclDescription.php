<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\DescribeAcls;

use Longyan\Kafka\Protocol\AbstractStruct;
use Longyan\Kafka\Protocol\ProtocolField;

class AclDescription extends AbstractStruct
{
    /**
     * The ACL principal.
     *
     * @var string
     */
    protected $principal;

    /**
     * The ACL host.
     *
     * @var string
     */
    protected $host;

    /**
     * The ACL operation.
     *
     * @var int
     */
    protected $operation;

    /**
     * The ACL permission type.
     *
     * @var int
     */
    protected $permissionType;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
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
