<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\OffsetDelete;

use longlang\phpkafka\Protocol\AbstractResponse;
use longlang\phpkafka\Protocol\ProtocolField;

class OffsetDeleteResponse extends AbstractResponse
{
    /**
     * The top-level error code, or 0 if there was no error.
     *
     * @var int
     */
    protected $errorCode = 0;

    /**
     * The duration in milliseconds for which the request was throttled due to a quota violation, or zero if the request did not violate any quota.
     *
     * @var int
     */
    protected $throttleTimeMs = 0;

    /**
     * The responses for each topic.
     *
     * @var OffsetDeleteResponseTopic[]
     */
    protected $topics = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('errorCode', 'int16', false, [0], [], [], [], null),
                new ProtocolField('throttleTimeMs', 'int32', false, [0], [], [], [], null),
                new ProtocolField('topics', OffsetDeleteResponseTopic::class, true, [0], [], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 47;
    }

    public function getFlexibleVersions(): array
    {
        return [];
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
     * @return OffsetDeleteResponseTopic[]
     */
    public function getTopics(): array
    {
        return $this->topics;
    }

    /**
     * @param OffsetDeleteResponseTopic[] $topics
     */
    public function setTopics(array $topics): self
    {
        $this->topics = $topics;

        return $this;
    }
}
