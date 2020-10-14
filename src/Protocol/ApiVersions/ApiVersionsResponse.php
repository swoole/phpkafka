<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\ApiVersions;

use longlang\phpkafka\Protocol\AbstractResponse;
use longlang\phpkafka\Protocol\ProtocolField;

class ApiVersionsResponse extends AbstractResponse
{
    /**
     * The top-level error code.
     *
     * @var int
     */
    protected $errorCode = 0;

    /**
     * The APIs supported by the broker.
     *
     * @var ApiVersionsResponseKey[]
     */
    protected $apiKeys = [];

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
                new ProtocolField('errorCode', 'int16', false, [0, 1, 2, 3], [3], [], [], null),
                new ProtocolField('apiKeys', ApiVersionsResponseKey::class, true, [0, 1, 2, 3], [3], [], [], null),
                new ProtocolField('throttleTimeMs', 'int32', false, [1, 2, 3], [3], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 18;
    }

    public function getFlexibleVersions(): array
    {
        return [3];
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

    /**
     * @return ApiVersionsResponseKey[]
     */
    public function getApiKeys(): array
    {
        return $this->apiKeys;
    }

    /**
     * @param ApiVersionsResponseKey[] $apiKeys
     */
    public function setApiKeys(array $apiKeys): self
    {
        $this->apiKeys = $apiKeys;

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
