<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\CreateAcls;

use longlang\phpkafka\Protocol\AbstractRequest;
use longlang\phpkafka\Protocol\ProtocolField;

class CreateAclsRequest extends AbstractRequest
{
    /**
     * The ACLs that we want to create.
     *
     * @var AclCreation[]
     */
    protected $creations = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('creations', AclCreation::class, true, [0, 1, 2], [2], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 30;
    }

    public function getMaxSupportedVersion(): int
    {
        return 2;
    }

    public function getFlexibleVersions(): array
    {
        return [2];
    }

    /**
     * @return AclCreation[]
     */
    public function getCreations(): array
    {
        return $this->creations;
    }

    /**
     * @param AclCreation[] $creations
     */
    public function setCreations(array $creations): self
    {
        $this->creations = $creations;

        return $this;
    }
}
