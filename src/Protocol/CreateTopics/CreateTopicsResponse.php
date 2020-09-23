<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\CreateTopics;

use Longyan\Kafka\Protocol\AbstractResponse;
use Longyan\Kafka\Protocol\ApiKeys;
use Longyan\Kafka\Protocol\ProtocolField;

class CreateTopicsResponse extends AbstractResponse
{
    /**
     * Results for each topic we tried to create.
     *
     * @var TopicResult[]
     */
    protected $topics;

    /**
     * The duration in milliseconds for which the request was throttled due to a quota violation, or zero if the request did not violate any quota.
     *
     * @var int
     */
    protected $throttleTimeMs;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('throttleTimeMs', 'Int32', null, 2),
                new ProtocolField('topics', TopicResult::class, 'CompactArray', 5),
                new ProtocolField('topics', TopicResult::class, 'ArrayInt32', 0),
            ];
            self::$taggedFieldses[self::class] = [];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return ApiKeys::PROTOCOL_CREATE_TOPICS;
    }

    public function getFlexibleVersions(): ?int
    {
        return 5;
    }

    /**
     * @return TopicResult[]
     */
    public function getTopics(): array
    {
        return $this->topics;
    }

    /**
     * @param TopicResult[] $topics
     */
    public function setTopics(array $topics): self
    {
        $this->topics = $topics;

        return $this;
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
}
