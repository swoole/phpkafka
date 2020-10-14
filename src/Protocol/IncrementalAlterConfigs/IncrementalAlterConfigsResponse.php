<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\IncrementalAlterConfigs;

use longlang\phpkafka\Protocol\AbstractResponse;
use longlang\phpkafka\Protocol\ProtocolField;

class IncrementalAlterConfigsResponse extends AbstractResponse
{
    /**
     * Duration in milliseconds for which the request was throttled due to a quota violation, or zero if the request did not violate any quota.
     *
     * @var int
     */
    protected $throttleTimeMs = 0;

    /**
     * The responses for each resource.
     *
     * @var AlterConfigsResourceResponse[]
     */
    protected $responses = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('throttleTimeMs', 'int32', false, [0, 1], [1], [], [], null),
                new ProtocolField('responses', AlterConfigsResourceResponse::class, true, [0, 1], [1], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 44;
    }

    public function getFlexibleVersions(): array
    {
        return [1];
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
     * @return AlterConfigsResourceResponse[]
     */
    public function getResponses(): array
    {
        return $this->responses;
    }

    /**
     * @param AlterConfigsResourceResponse[] $responses
     */
    public function setResponses(array $responses): self
    {
        $this->responses = $responses;

        return $this;
    }
}
