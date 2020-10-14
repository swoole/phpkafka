<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\Produce;

use longlang\phpkafka\Protocol\AbstractResponse;
use longlang\phpkafka\Protocol\ProtocolField;

class ProduceResponse extends AbstractResponse
{
    /**
     * Each produce response.
     *
     * @var TopicProduceResponse[]
     */
    protected $responses = [];

    /**
     * The duration in milliseconds for which the request was throttled due to a quota violation, or zero if the request did not violate any quota.
     *
     * @var int
     */
    protected $throttleTimeMs = 0;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('responses', TopicProduceResponse::class, true, [0, 1, 2, 3, 4, 5, 6, 7, 8], [], [], [], null),
                new ProtocolField('throttleTimeMs', 'int32', false, [1, 2, 3, 4, 5, 6, 7, 8], [], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 0;
    }

    public function getFlexibleVersions(): array
    {
        return [];
    }

    /**
     * @return TopicProduceResponse[]
     */
    public function getResponses(): array
    {
        return $this->responses;
    }

    /**
     * @param TopicProduceResponse[] $responses
     */
    public function setResponses(array $responses): self
    {
        $this->responses = $responses;

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
