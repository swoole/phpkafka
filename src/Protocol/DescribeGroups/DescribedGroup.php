<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\DescribeGroups;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolField;

class DescribedGroup extends AbstractStruct
{
    /**
     * The describe error, or 0 if there was no error.
     *
     * @var int
     */
    protected $errorCode = 0;

    /**
     * The group ID string.
     *
     * @var string
     */
    protected $groupId = '';

    /**
     * The group state string, or the empty string.
     *
     * @var string
     */
    protected $groupState = '';

    /**
     * The group protocol type, or the empty string.
     *
     * @var string
     */
    protected $protocolType = '';

    /**
     * The group protocol data, or the empty string.
     *
     * @var string
     */
    protected $protocolData = '';

    /**
     * The group members.
     *
     * @var DescribedGroupMember[]
     */
    protected $members = [];

    /**
     * 32-bit bitfield to represent authorized operations for this group.
     *
     * @var int
     */
    protected $authorizedOperations = -2147483648;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('errorCode', 'int16', false, [0, 1, 2, 3, 4, 5], [5], [], [], null),
                new ProtocolField('groupId', 'string', false, [0, 1, 2, 3, 4, 5], [5], [], [], null),
                new ProtocolField('groupState', 'string', false, [0, 1, 2, 3, 4, 5], [5], [], [], null),
                new ProtocolField('protocolType', 'string', false, [0, 1, 2, 3, 4, 5], [5], [], [], null),
                new ProtocolField('protocolData', 'string', false, [0, 1, 2, 3, 4, 5], [5], [], [], null),
                new ProtocolField('members', DescribedGroupMember::class, true, [0, 1, 2, 3, 4, 5], [5], [], [], null),
                new ProtocolField('authorizedOperations', 'int32', false, [3, 4, 5], [5], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [5];
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

    public function getGroupId(): string
    {
        return $this->groupId;
    }

    public function setGroupId(string $groupId): self
    {
        $this->groupId = $groupId;

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

    public function getProtocolType(): string
    {
        return $this->protocolType;
    }

    public function setProtocolType(string $protocolType): self
    {
        $this->protocolType = $protocolType;

        return $this;
    }

    public function getProtocolData(): string
    {
        return $this->protocolData;
    }

    public function setProtocolData(string $protocolData): self
    {
        $this->protocolData = $protocolData;

        return $this;
    }

    /**
     * @return DescribedGroupMember[]
     */
    public function getMembers(): array
    {
        return $this->members;
    }

    /**
     * @param DescribedGroupMember[] $members
     */
    public function setMembers(array $members): self
    {
        $this->members = $members;

        return $this;
    }

    public function getAuthorizedOperations(): int
    {
        return $this->authorizedOperations;
    }

    public function setAuthorizedOperations(int $authorizedOperations): self
    {
        $this->authorizedOperations = $authorizedOperations;

        return $this;
    }
}
