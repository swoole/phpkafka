<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\CreateDelegationToken;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolField;

class CreatableRenewers extends AbstractStruct
{
    /**
     * The type of the Kafka principal.
     *
     * @var string
     */
    protected $principalType = '';

    /**
     * The name of the Kafka principal.
     *
     * @var string
     */
    protected $principalName = '';

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('principalType', 'string', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('principalName', 'string', false, [0, 1, 2], [2], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [2];
    }

    public function getPrincipalType(): string
    {
        return $this->principalType;
    }

    public function setPrincipalType(string $principalType): self
    {
        $this->principalType = $principalType;

        return $this;
    }

    public function getPrincipalName(): string
    {
        return $this->principalName;
    }

    public function setPrincipalName(string $principalName): self
    {
        $this->principalName = $principalName;

        return $this;
    }
}
