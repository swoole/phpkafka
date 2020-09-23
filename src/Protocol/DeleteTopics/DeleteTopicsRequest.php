<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\DeleteTopics;

use Longyan\Kafka\Protocol\AbstractRequest;
use Longyan\Kafka\Protocol\ApiKeys;
use Longyan\Kafka\Protocol\ProtocolField;

class DeleteTopicsRequest extends AbstractRequest
{
    /**
     * The names of the topics to delete.
     *
     * @var string[]
     */
    protected $topicNames = [];

    /**
     * 	The length of time in milliseconds to wait for the deletions to complete.
     *
     * @var int
     */
    protected $timeoutMs = 0;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('topicNames', 'CompactString', 'CompactArray', 4),
                new ProtocolField('topicNames', 'String16', 'ArrayInt32', 0),
                new ProtocolField('timeoutMs', 'Int32', null, 0),
            ];
            self::$taggedFieldses[self::class] = [];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return ApiKeys::PROTOCOL_DELETE_TOPICS;
    }

    public function getMaxSupportedVersion(): int
    {
        return 4;
    }

    public function getFlexibleVersions(): ?int
    {
        return 4;
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
}
