<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\Fetch;

use longlang\phpkafka\Protocol\AbstractResponse;
use longlang\phpkafka\Protocol\ProtocolField;

class FetchResponse extends AbstractResponse
{
    /**
     * The duration in milliseconds for which the request was throttled due to a quota violation, or zero if the request did not violate any quota.
     *
     * @var int
     */
    protected $throttleTimeMs = 0;

    /**
     * The top level response error code.
     *
     * @var int
     */
    protected $errorCode = 0;

    /**
     * The fetch session ID, or 0 if this is not part of a fetch session.
     *
     * @var int
     */
    protected $sessionId = 0;

    /**
     * The response topics.
     *
     * @var FetchableTopicResponse[]
     */
    protected $topics = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('throttleTimeMs', 'int32', false, [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11], [], [], [], null),
                new ProtocolField('errorCode', 'int16', false, [7, 8, 9, 10, 11], [], [], [], null),
                new ProtocolField('sessionId', 'int32', false, [7, 8, 9, 10, 11], [], [], [], null),
                new ProtocolField('topics', FetchableTopicResponse::class, true, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11], [], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 1;
    }

    public function getFlexibleVersions(): array
    {
        return [];
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

    public function getErrorCode(): int
    {
        return $this->errorCode;
    }

    public function setErrorCode(int $errorCode): self
    {
        $this->errorCode = $errorCode;

        return $this;
    }

    public function getSessionId(): int
    {
        return $this->sessionId;
    }

    public function setSessionId(int $sessionId): self
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    /**
     * @return FetchableTopicResponse[]
     */
    public function getTopics(): array
    {
        return $this->topics;
    }

    /**
     * @param FetchableTopicResponse[] $topics
     */
    public function setTopics(array $topics): self
    {
        $this->topics = $topics;

        return $this;
    }
}
