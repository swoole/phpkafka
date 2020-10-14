<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\ListGroups;

use longlang\phpkafka\Protocol\AbstractRequest;
use longlang\phpkafka\Protocol\ProtocolField;

class ListGroupsRequest extends AbstractRequest
{
    /**
     * The states of the groups we want to list. If empty all groups are returned with their state.
     *
     * @var string[]
     */
    protected $statesFilter = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('statesFilter', 'string', true, [4], [3, 4], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 16;
    }

    public function getMaxSupportedVersion(): int
    {
        return 4;
    }

    public function getFlexibleVersions(): array
    {
        return [3, 4];
    }

    /**
     * @return string[]
     */
    public function getStatesFilter(): array
    {
        return $this->statesFilter;
    }

    /**
     * @param string[] $statesFilter
     */
    public function setStatesFilter(array $statesFilter): self
    {
        $this->statesFilter = $statesFilter;

        return $this;
    }
}
