<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\DescribeGroups;

use Longyan\Kafka\Protocol\AbstractRequest;
use Longyan\Kafka\Protocol\ProtocolField;

class DescribeGroupsRequest extends AbstractRequest
{
    /**
     * The names of the groups to describe.
     *
     * @var string[]
     */
    protected $groupId = [];

    /**
     * Whether to include authorized operations.
     *
     * @var bool
     */
    protected $includeAuthorizedOperations;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('groupId', 'string', true, [0, 1, 2, 3, 4, 5], [5], [], [], null),
                new ProtocolField('includeAuthorizedOperations', 'bool', false, [3, 4, 5], [5], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 15;
    }

    public function getMaxSupportedVersion(): int
    {
        return 5;
    }

    public function getFlexibleVersions(): array
    {
        return [5];
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

    public function getIncludeAuthorizedOperations(): bool
    {
        return $this->includeAuthorizedOperations;
    }

    public function setIncludeAuthorizedOperations(bool $includeAuthorizedOperations): self
    {
        $this->includeAuthorizedOperations = $includeAuthorizedOperations;

        return $this;
    }
}
