<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\AlterPartitionReassignments;

use longlang\phpkafka\Protocol\AbstractRequest;
use longlang\phpkafka\Protocol\ProtocolField;

class AlterPartitionReassignmentsRequest extends AbstractRequest
{
    /**
     * The time in ms to wait for the request to complete.
     *
     * @var int
     */
    protected $timeoutMs = 60000;

    /**
     * The topics to reassign.
     *
     * @var ReassignableTopic[]
     */
    protected $topics = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('timeoutMs', 'int32', false, [0], [0], [], [], null),
                new ProtocolField('topics', ReassignableTopic::class, true, [0], [0], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 45;
    }

    public function getMaxSupportedVersion(): int
    {
        return 0;
    }

    public function getFlexibleVersions(): array
    {
        return [0];
    }

    public function getTimeoutMs(): int
    {
        return $this->timeoutMs;
    }

    public function setTimeoutMs(int $timeoutMs): self
    {
        $this->timeoutMs = $timeoutMs;

        return $this;
    }

    /**
     * @return ReassignableTopic[]
     */
    public function getTopics(): array
    {
        return $this->topics;
    }

    /**
     * @param ReassignableTopic[] $topics
     */
    public function setTopics(array $topics): self
    {
        $this->topics = $topics;

        return $this;
    }
}
