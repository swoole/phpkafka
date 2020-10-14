<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\OffsetCommit;

use longlang\phpkafka\Protocol\AbstractRequest;
use longlang\phpkafka\Protocol\ProtocolField;

class OffsetCommitRequest extends AbstractRequest
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
    protected $generationId = -1;

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
     * The time period in ms to retain the offset.
     *
     * @var int
     */
    protected $retentionTimeMs = -1;

    /**
     * The topics to commit offsets for.
     *
     * @var OffsetCommitRequestTopic[]
     */
    protected $topics = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('groupId', 'string', false, [0, 1, 2, 3, 4, 5, 6, 7, 8], [8], [], [], null),
                new ProtocolField('generationId', 'int32', false, [1, 2, 3, 4, 5, 6, 7, 8], [8], [], [], null),
                new ProtocolField('memberId', 'string', false, [1, 2, 3, 4, 5, 6, 7, 8], [8], [], [], null),
                new ProtocolField('groupInstanceId', 'string', false, [7, 8], [8], [7, 8], [], null),
                new ProtocolField('retentionTimeMs', 'int64', false, [2, 3, 4], [8], [], [], null),
                new ProtocolField('topics', OffsetCommitRequestTopic::class, true, [0, 1, 2, 3, 4, 5, 6, 7, 8], [8], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 8;
    }

    public function getMaxSupportedVersion(): int
    {
        return 8;
    }

    public function getFlexibleVersions(): array
    {
        return [8];
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

    public function getRetentionTimeMs(): int
    {
        return $this->retentionTimeMs;
    }

    public function setRetentionTimeMs(int $retentionTimeMs): self
    {
        $this->retentionTimeMs = $retentionTimeMs;

        return $this;
    }

    /**
     * @return OffsetCommitRequestTopic[]
     */
    public function getTopics(): array
    {
        return $this->topics;
    }

    /**
     * @param OffsetCommitRequestTopic[] $topics
     */
    public function setTopics(array $topics): self
    {
        $this->topics = $topics;

        return $this;
    }
}
