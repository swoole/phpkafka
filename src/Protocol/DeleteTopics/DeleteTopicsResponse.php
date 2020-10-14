<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\DeleteTopics;

use longlang\phpkafka\Protocol\AbstractResponse;
use longlang\phpkafka\Protocol\ProtocolField;

class DeleteTopicsResponse extends AbstractResponse
{
    /**
     * The duration in milliseconds for which the request was throttled due to a quota violation, or zero if the request did not violate any quota.
     *
     * @var int
     */
    protected $throttleTimeMs = 0;

    /**
     * The results for each topic we tried to delete.
     *
     * @var DeletableTopicResult[]
     */
    protected $responses = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('throttleTimeMs', 'int32', false, [1, 2, 3, 4], [4], [], [], null),
                new ProtocolField('responses', DeletableTopicResult::class, true, [0, 1, 2, 3, 4], [4], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 20;
    }

    public function getFlexibleVersions(): array
    {
        return [4];
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

    /**
     * @return DeletableTopicResult[]
     */
    public function getResponses(): array
    {
        return $this->responses;
    }

    /**
     * @param DeletableTopicResult[] $responses
     */
    public function setResponses(array $responses): self
    {
        $this->responses = $responses;

        return $this;
    }
}
