<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\DeleteTopics;

use longlang\phpkafka\Protocol\AbstractRequest;
use longlang\phpkafka\Protocol\ProtocolField;

class DeleteTopicsRequest extends AbstractRequest
{
    /**
     * The names of the topics to delete.
     *
     * @var string[]
     */
    protected $topicNames = [];

    /**
     * The length of time in milliseconds to wait for the deletions to complete.
     *
     * @var int
     */
    protected $timeoutMs = 0;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('topicNames', 'string', true, [0, 1, 2, 3, 4], [4], [], [], null),
                new ProtocolField('timeoutMs', 'int32', false, [0, 1, 2, 3, 4], [4], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 20;
    }

    public function getMaxSupportedVersion(): int
    {
        return 4;
    }

    public function getFlexibleVersions(): array
    {
        return [4];
    }

    /**
     * @return string[]
     */
    public function getTopicNames(): array
    {
        return $this->topicNames;
    }

    /**
     * @param string[] $topicNames
     */
    public function setTopicNames(array $topicNames): self
    {
        $this->topicNames = $topicNames;

        return $this;
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
}
