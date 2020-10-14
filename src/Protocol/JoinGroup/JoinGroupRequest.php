<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\JoinGroup;

use longlang\phpkafka\Protocol\AbstractRequest;
use longlang\phpkafka\Protocol\ProtocolField;

class JoinGroupRequest extends AbstractRequest
{
    /**
     * The group identifier.
     *
     * @var string
     */
    protected $groupId = '';

    /**
     * The coordinator considers the consumer dead if it receives no heartbeat after this timeout in milliseconds.
     *
     * @var int
     */
    protected $sessionTimeoutMs = 0;

    /**
     * The maximum time in milliseconds that the coordinator will wait for each member to rejoin when rebalancing the group.
     *
     * @var int
     */
    protected $rebalanceTimeoutMs = -1;

    /**
     * The member id assigned by the group coordinator.
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
     * The unique name the for class of protocols implemented by the group we want to join.
     *
     * @var string
     */
    protected $protocolType = '';

    /**
     * The list of protocols that the member supports.
     *
     * @var JoinGroupRequestProtocol[]
     */
    protected $protocols = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('groupId', 'string', false, [0, 1, 2, 3, 4, 5, 6, 7], [6, 7], [], [], null),
                new ProtocolField('sessionTimeoutMs', 'int32', false, [0, 1, 2, 3, 4, 5, 6, 7], [6, 7], [], [], null),
                new ProtocolField('rebalanceTimeoutMs', 'int32', false, [1, 2, 3, 4, 5, 6, 7], [6, 7], [], [], null),
                new ProtocolField('memberId', 'string', false, [0, 1, 2, 3, 4, 5, 6, 7], [6, 7], [], [], null),
                new ProtocolField('groupInstanceId', 'string', false, [5, 6, 7], [6, 7], [5, 6, 7], [], null),
                new ProtocolField('protocolType', 'string', false, [0, 1, 2, 3, 4, 5, 6, 7], [6, 7], [], [], null),
                new ProtocolField('protocols', JoinGroupRequestProtocol::class, true, [0, 1, 2, 3, 4, 5, 6, 7], [6, 7], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 11;
    }

    public function getMaxSupportedVersion(): int
    {
        return 7;
    }

    public function getFlexibleVersions(): array
    {
        return [6, 7];
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

    public function getSessionTimeoutMs(): int
    {
        return $this->sessionTimeoutMs;
    }

    public function setSessionTimeoutMs(int $sessionTimeoutMs): self
    {
        $this->sessionTimeoutMs = $sessionTimeoutMs;

        return $this;
    }

    public function getRebalanceTimeoutMs(): int
    {
        return $this->rebalanceTimeoutMs;
    }

    public function setRebalanceTimeoutMs(int $rebalanceTimeoutMs): self
    {
        $this->rebalanceTimeoutMs = $rebalanceTimeoutMs;

        return $this;
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

    public function getProtocolType(): string
    {
        return $this->protocolType;
    }

    public function setProtocolType(string $protocolType): self
    {
        $this->protocolType = $protocolType;

        return $this;
    }

    /**
     * @return JoinGroupRequestProtocol[]
     */
    public function getProtocols(): array
    {
        return $this->protocols;
    }

    /**
     * @param JoinGroupRequestProtocol[] $protocols
     */
    public function setProtocols(array $protocols): self
    {
        $this->protocols = $protocols;

        return $this;
    }
}
