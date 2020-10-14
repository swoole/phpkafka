<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\OffsetFetch;

use longlang\phpkafka\Protocol\AbstractResponse;
use longlang\phpkafka\Protocol\ProtocolField;

class OffsetFetchResponse extends AbstractResponse
{
    /**
     * The duration in milliseconds for which the request was throttled due to a quota violation, or zero if the request did not violate any quota.
     *
     * @var int
     */
    protected $throttleTimeMs = 0;

    /**
     * The responses per topic.
     *
     * @var OffsetFetchResponseTopic[]
     */
    protected $topics = [];

    /**
     * The top-level error code, or 0 if there was no error.
     *
     * @var int
     */
    protected $errorCode = 0;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('throttleTimeMs', 'int32', false, [3, 4, 5, 6, 7], [6, 7], [], [], null),
                new ProtocolField('topics', OffsetFetchResponseTopic::class, true, [0, 1, 2, 3, 4, 5, 6, 7], [6, 7], [], [], null),
                new ProtocolField('errorCode', 'int16', false, [2, 3, 4, 5, 6, 7], [6, 7], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 9;
    }

    public function getFlexibleVersions(): array
    {
        return [6, 7];
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
     * @return OffsetFetchResponseTopic[]
     */
    public function getTopics(): array
    {
        return $this->topics;
    }

    /**
     * @param OffsetFetchResponseTopic[] $topics
     */
    public function setTopics(array $topics): self
    {
        $this->topics = $topics;

        return $this;
    }

    public function getErrorCode(): int
    {
        return $this->errorCode;
    }

    public function setErrorCode(int $errorCode): self
    {
        $this->errorCode = $errorCode;

        return $this;
    }
}
