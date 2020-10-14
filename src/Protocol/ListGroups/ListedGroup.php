<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\ListGroups;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolField;

class ListedGroup extends AbstractStruct
{
    /**
     * The group ID.
     *
     * @var string
     */
    protected $groupId = '';

    /**
     * The group protocol type.
     *
     * @var string
     */
    protected $protocolType = '';

    /**
     * The group state name.
     *
     * @var string
     */
    protected $groupState = '';

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('groupId', 'string', false, [0, 1, 2, 3, 4], [3, 4], [], [], null),
                new ProtocolField('protocolType', 'string', false, [0, 1, 2, 3, 4], [3, 4], [], [], null),
                new ProtocolField('groupState', 'string', false, [4], [3, 4], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [3, 4];
    }

    public function getGroupId(): string
    {
        return $this->groupId;
    }

    public function setGroupId(string $groupId): self
    {
        $this->groupId = $groupId;

        return $this;
    }

    public function getProtocolType(): string
    {
        return $this->protocolType;
    }

    public function setProtocolType(string $protocolType): self
    {
        $this->protocolType = $protocolType;

        return $this;
    }

    public function getGroupState(): string
    {
        return $this->groupState;
    }

    public function setGroupState(string $groupState): self
    {
        $this->groupState = $groupState;

        return $this;
    }
}
