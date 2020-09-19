<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\ApiVersions;

use Longyan\Kafka\Protocol\ApiKeys;
use Longyan\Kafka\Protocol\ProtocolField;
use Longyan\Kafka\Protocol\AbstractResponse;

class ApiVersionsResponse extends AbstractResponse
{
    /**
     * The top-level error code.
     * 
     * @var int
     */
    protected $errorCode;

    /**
     * The APIs supported by the broker.
     * 
     * @var \Longyan\Kafka\Protocol\ApiVersions\ApiKeys[]
     */
    protected $apiKeys;

    /**
     * The duration in milliseconds for which the request was throttled due to a quota violation, or zero if the request did not violate any quota.
     *
     * @var int
     */
    protected $throttleTimeMs;

    public function __construct()
    {
        $this->map = [
            'errorCode'      => new ProtocolField('Int16', null, 0),
            'apiKeys'        => new ProtocolField(\Longyan\Kafka\Protocol\ApiVersions\ApiKeys::class, 'ArrayInt32', 0),
            'throttleTimeMs' => new ProtocolField('Int32', null, 1),
        ];
    }

    public function getRequestApiKey(): ?int
    {
        return ApiKeys::PROTOCOL_API_VERSIONS;
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
     * @return ApiKeys[]
     */
    public function getApiKeys(): array
    {
        return $this->apiKeys;
    }

    /**
     * @param ApiKeys[] $apiKeys
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
