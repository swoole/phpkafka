<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\OffsetDelete;

use longlang\phpkafka\Protocol\AbstractRequest;
use longlang\phpkafka\Protocol\ProtocolField;

class OffsetDeleteRequest extends AbstractRequest
{
    /**
     * The unique group identifier.
     *
     * @var string
     */
    protected $groupId = '';

    /**
     * The topics to delete offsets for.
     *
     * @var OffsetDeleteRequestTopic[]
     */
    protected $topics = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('groupId', 'string', false, [0], [], [], [], null),
                new ProtocolField('topics', OffsetDeleteRequestTopic::class, true, [0], [], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 47;
    }

    public function getMaxSupportedVersion(): int
    {
        return 0;
    }

    public function getFlexibleVersions(): array
    {
        return [];
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
     * @return OffsetDeleteRequestTopic[]
     */
    public function getTopics(): array
    {
        return $this->topics;
    }

    /**
     * @param OffsetDeleteRequestTopic[] $topics
     */
    public function setTopics(array $topics): self
    {
        $this->topics = $topics;

        return $this;
    }
}
