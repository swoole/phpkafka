<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\DeleteGroups;

use Longyan\Kafka\Protocol\AbstractRequest;
use Longyan\Kafka\Protocol\ProtocolField;

class DeleteGroupsRequest extends AbstractRequest
{
    /**
     * The group names to delete.
     *
     * @var string[]
     */
    protected $groupId = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('groupId', 'string', true, [0, 1, 2], [2], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 42;
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
     * @return string[]
     */
    public function getGroupId(): array
    {
        return $this->groupId;
    }

    /**
     * @param string[] $groupId
     */
    public function setGroupId(array $groupId): self
    {
        $this->groupId = $groupId;

        return $this;
    }
}
