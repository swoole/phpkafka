<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\DeleteTopics;

use Longyan\Kafka\Protocol\AbstractRequest;
use Longyan\Kafka\Protocol\ProtocolField;

class DeleteTopicsRequest extends AbstractRequest
{
    /**
     * The names of the topics to delete.
     *
     * @var string[]
     */
    protected $topicName = [];

    /**
     * The length of time in milliseconds to wait for the deletions to complete.
     *
     * @var int
     */
    protected $timeoutMs;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('topicName', 'string', true, [0, 1, 2, 3, 4], [4], [], [], null),
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
    public function getTopicName(): array
    {
        return $this->topicName;
    }

    /**
     * @param string[] $topicName
     */
    public function setTopicName(array $topicName): self
    {
        $this->topicName = $topicName;

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
