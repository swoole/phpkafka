<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\OffsetForLeaderEpoch;

use longlang\phpkafka\Protocol\AbstractRequest;
use longlang\phpkafka\Protocol\ProtocolField;

class OffsetForLeaderEpochRequest extends AbstractRequest
{
    /**
     * The broker ID of the follower, of -1 if this request is from a consumer.
     *
     * @var int
     */
    protected $replicaId = -2;

    /**
     * Each topic to get offsets for.
     *
     * @var OffsetForLeaderTopic[]
     */
    protected $topics = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('replicaId', 'int32', false, [3], [], [], [], null),
                new ProtocolField('topics', OffsetForLeaderTopic::class, true, [0, 1, 2, 3], [], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 23;
    }

    public function getMaxSupportedVersion(): int
    {
        return 3;
    }

    public function getFlexibleVersions(): array
    {
        return [];
    }

    public function getReplicaId(): int
    {
        return $this->replicaId;
    }

    public function setReplicaId(int $replicaId): self
    {
        $this->replicaId = $replicaId;

        return $this;
    }

    /**
     * @return OffsetForLeaderTopic[]
     */
    public function getTopics(): array
    {
        return $this->topics;
    }

    /**
     * @param OffsetForLeaderTopic[] $topics
     */
    public function setTopics(array $topics): self
    {
        $this->topics = $topics;

        return $this;
    }
}
