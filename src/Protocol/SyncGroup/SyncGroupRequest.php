<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\SyncGroup;

use longlang\phpkafka\Protocol\AbstractRequest;
use longlang\phpkafka\Protocol\ProtocolField;

class SyncGroupRequest extends AbstractRequest
{
    /**
     * The unique group identifier.
     *
     * @var string
     */
    protected $groupId = '';

    /**
     * The generation of the group.
     *
     * @var int
     */
    protected $generationId = 0;

    /**
     * The member ID assigned by the group.
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
     * The group protocol type.
     *
     * @var string|null
     */
    protected $protocolType = null;

    /**
     * The group protocol name.
     *
     * @var string|null
     */
    protected $protocolName = null;

    /**
     * Each assignment.
     *
     * @var SyncGroupRequestAssignment[]
     */
    protected $assignments = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('groupId', 'string', false, [0, 1, 2, 3, 4, 5], [4, 5], [], [], null),
                new ProtocolField('generationId', 'int32', false, [0, 1, 2, 3, 4, 5], [4, 5], [], [], null),
                new ProtocolField('memberId', 'string', false, [0, 1, 2, 3, 4, 5], [4, 5], [], [], null),
                new ProtocolField('groupInstanceId', 'string', false, [3, 4, 5], [4, 5], [3, 4, 5], [], null),
                new ProtocolField('protocolType', 'string', false, [5], [4, 5], [5], [], null),
                new ProtocolField('protocolName', 'string', false, [5], [4, 5], [5], [], null),
                new ProtocolField('assignments', SyncGroupRequestAssignment::class, true, [0, 1, 2, 3, 4, 5], [4, 5], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 14;
    }

    public function getMaxSupportedVersion(): int
    {
        return 5;
    }

    public function getFlexibleVersions(): array
    {
        return [4, 5];
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

    public function getGenerationId(): int
    {
        return $this->generationId;
    }

    public function setGenerationId(int $generationId): self
    {
        $this->generationId = $generationId;

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

    /**
     * @return SyncGroupRequestAssignment[]
     */
    public function getAssignments(): array
    {
        return $this->assignments;
    }

    /**
     * @param SyncGroupRequestAssignment[] $assignments
     */
    public function setAssignments(array $assignments): self
    {
        $this->assignments = $assignments;

        return $this;
    }
}
