<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\DescribeGroups;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolField;

class DescribedGroupMember extends AbstractStruct
{
    /**
     * The member ID assigned by the group coordinator.
     *
     * @var string
     */
    protected $memberId = '';

    /**
     * The unique identifier of the consumer instance provided by end user.
     *
     * @var string|null
     */
    protected $groupInstanceId = null;

    /**
     * The client ID used in the member's latest join group request.
     *
     * @var string
     */
    protected $clientId = '';

    /**
     * The client host.
     *
     * @var string
     */
    protected $clientHost = '';

    /**
     * The metadata corresponding to the current group protocol in use.
     *
     * @var string
     */
    protected $memberMetadata = '';

    /**
     * The current assignment provided by the group leader.
     *
     * @var string
     */
    protected $memberAssignment = '';

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('memberId', 'string', false, [0, 1, 2, 3, 4, 5], [5], [], [], null),
                new ProtocolField('groupInstanceId', 'string', false, [4, 5], [5], [4, 5], [], null),
                new ProtocolField('clientId', 'string', false, [0, 1, 2, 3, 4, 5], [5], [], [], null),
                new ProtocolField('clientHost', 'string', false, [0, 1, 2, 3, 4, 5], [5], [], [], null),
                new ProtocolField('memberMetadata', 'bytes', false, [0, 1, 2, 3, 4, 5], [5], [], [], null),
                new ProtocolField('memberAssignment', 'bytes', false, [0, 1, 2, 3, 4, 5], [5], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [5];
    }

    public function getMemberId(): string
    {
        return $this->memberId;
    }

    public function setMemberId(string $memberId): self
    {
        $this->memberId = $memberId;

        return $this;
    }

    public function getGroupInstanceId(): ?string
    {
        return $this->groupInstanceId;
    }

    public function setGroupInstanceId(?string $groupInstanceId): self
    {
        $this->groupInstanceId = $groupInstanceId;

        return $this;
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function setClientId(string $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }

    public function getClientHost(): string
    {
        return $this->clientHost;
    }

    public function setClientHost(string $clientHost): self
    {
        $this->clientHost = $clientHost;

        return $this;
    }

    public function getMemberMetadata(): string
    {
        return $this->memberMetadata;
    }

    public function setMemberMetadata(string $memberMetadata): self
    {
        $this->memberMetadata = $memberMetadata;

        return $this;
    }

    public function getMemberAssignment(): string
    {
        return $this->memberAssignment;
    }

    public function setMemberAssignment(string $memberAssignment): self
    {
        $this->memberAssignment = $memberAssignment;

        return $this;
    }
}
