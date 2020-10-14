<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\OffsetFetch;

use longlang\phpkafka\Protocol\AbstractRequest;
use longlang\phpkafka\Protocol\ProtocolField;

class OffsetFetchRequest extends AbstractRequest
{
    /**
     * The group to fetch offsets for.
     *
     * @var string
     */
    protected $groupId = '';

    /**
     * Each topic we would like to fetch offsets for, or null to fetch offsets for all topics.
     *
     * @var OffsetFetchRequestTopic[]|null
     */
    protected $topics = null;

    /**
     * Whether broker should hold on returning unstable offsets but set a retriable error code for the partition.
     *
     * @var bool
     */
    protected $requireStable = false;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('groupId', 'string', false, [0, 1, 2, 3, 4, 5, 6, 7], [6, 7], [], [], null),
                new ProtocolField('topics', OffsetFetchRequestTopic::class, true, [0, 1, 2, 3, 4, 5, 6, 7], [6, 7], [2, 3, 4, 5, 6, 7], [], null),
                new ProtocolField('requireStable', 'bool', false, [7], [6, 7], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 9;
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

    /**
     * @return OffsetFetchRequestTopic[]|null
     */
    public function getTopics(): ?array
    {
        return $this->topics;
    }

    /**
     * @param OffsetFetchRequestTopic[]|null $topics
     */
    public function setTopics(?array $topics): self
    {
        $this->topics = $topics;

        return $this;
    }

    public function getRequireStable(): bool
    {
        return $this->requireStable;
    }

    public function setRequireStable(bool $requireStable): self
    {
        $this->requireStable = $requireStable;

        return $this;
    }
}
