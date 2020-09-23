<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\DeleteTopics;

use Longyan\Kafka\Protocol\AbstractResponse;
use Longyan\Kafka\Protocol\ApiKeys;
use Longyan\Kafka\Protocol\ProtocolField;

class DeleteTopicsResponse extends AbstractResponse
{
    /**
     * The results for each topic we tried to delete.
     *
     * @var DeleteResponse[]
     */
    protected $responses;

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
                new ProtocolField('throttleTimeMs', 'Int32', null, 1),
                new ProtocolField('responses', DeleteResponse::class, 'CompactArray', 4),
                new ProtocolField('responses', DeleteResponse::class, 'ArrayInt32', 0),
            ];
            self::$taggedFieldses[self::class] = [];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return ApiKeys::PROTOCOL_DELETE_TOPICS;
    }

    public function getFlexibleVersions(): ?int
    {
        return 4;
    }

    /**
     * @return DeleteResponse[]
     */
    public function getResponses(): array
    {
        return $this->responses;
    }

    /**
     * @param DeleteResponse[] $responses
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
