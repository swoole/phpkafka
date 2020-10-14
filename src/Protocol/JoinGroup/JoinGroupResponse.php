<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\JoinGroup;

use longlang\phpkafka\Protocol\AbstractResponse;
use longlang\phpkafka\Protocol\ProtocolField;

class JoinGroupResponse extends AbstractResponse
{
    /**
     * The duration in milliseconds for which the request was throttled due to a quota violation, or zero if the request did not violate any quota.
     *
     * @var int
     */
    protected $throttleTimeMs = 0;

    /**
     * The error code, or 0 if there was no error.
     *
     * @var int
     */
    protected $errorCode = 0;

    /**
     * The generation ID of the group.
     *
     * @var int
     */
    protected $generationId = -1;

    /**
     * The group protocol name.
     *
     * @var string|null
     */
    protected $protocolType = null;

    /**
     * The group protocol selected by the coordinator.
     *
     * @var string|null
     */
    protected $protocolName = null;

    /**
     * The leader of the group.
     *
     * @var string
     */
    protected $leader = '';

    /**
     * The member ID assigned by the group coordinator.
     *
     * @var string
     */
    protected $memberId = '';

    /**
     * @var JoinGroupResponseMember[]
     */
    protected $members = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('throttleTimeMs', 'int32', false, [2, 3, 4, 5, 6, 7], [6, 7], [], [], null),
                new ProtocolField('errorCode', 'int16', false, [0, 1, 2, 3, 4, 5, 6, 7], [6, 7], [], [], null),
                new ProtocolField('generationId', 'int32', false, [0, 1, 2, 3, 4, 5, 6, 7], [6, 7], [], [], null),
                new ProtocolField('protocolType', 'string', false, [7], [6, 7], [7], [], null),
                new ProtocolField('protocolName', 'string', false, [0, 1, 2, 3, 4, 5, 6, 7], [6, 7], [7], [], null),
                new ProtocolField('leader', 'string', false, [0, 1, 2, 3, 4, 5, 6, 7], [6, 7], [], [], null),
                new ProtocolField('memberId', 'string', false, [0, 1, 2, 3, 4, 5, 6, 7], [6, 7], [], [], null),
                new ProtocolField('members', JoinGroupResponseMember::class, true, [0, 1, 2, 3, 4, 5, 6, 7], [6, 7], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 11;
    }

    public function getFlexibleVersions(): array
    {
        return [6, 7];
    }

    public function getThrottleTimeMs(): int
    {
        return $this->throttleTimeMs;
    }

    public function setThrottleTimeMs(int $throttleTimeMs): self
    {
        $this->throttleTimeMs = $throttleTimeMs;

        return $this;
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

    public function getGenerationId(): int
    {
        return $this->generationId;
    }

    public function setGenerationId(int $generationId): self
    {
        $this->generationId = $generationId;

        return $this;
    }

    public function getProtocolType(): ?string
    {
        return $this->protocolType;
    }

    public function setProtocolType(?string $protocolType): self
    {
        $this->protocolType = $protocolType;

        return $this;
    }

    public function getProtocolName(): ?string
    {
        return $this->protocolName;
    }

    public function setProtocolName(?string $protocolName): self
    {
        $this->protocolName = $protocolName;

        return $this;
    }

    public function getLeader(): string
    {
        return $this->leader;
    }

    public function setLeader(string $leader): self
    {
        $this->leader = $leader;

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

    /**
     * @return JoinGroupResponseMember[]
     */
    public function getMembers(): array
    {
        return $this->members;
    }

    /**
     * @param JoinGroupResponseMember[] $members
     */
    public function setMembers(array $members): self
    {
        $this->members = $members;

        return $this;
    }
}
