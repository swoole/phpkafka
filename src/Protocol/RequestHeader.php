<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol;

class RequestHeader extends AbstractStruct
{
    /**
     * The API key of this request.
     *
     * @var int
     */
    protected $requestApiKey;

    /**
     * The API version of this request.
     *
     * @var int
     */
    protected $requestApiVersion;

    /**
     * The correlation ID of this request.
     *
     * @var int
     */
    protected $correlationId;

    /**
     * The client ID string.
     *
     * @var string|null
     */
    protected $clientId;

    /**
     * @var int
     */
    private static $correlationIdIncrValue = 0;

    public function __construct(int $requestApiKey = 0, int $requestApiVersion = 0, ?int $correlationId = null, string $clientId = null)
    {
        $this->requestApiVersion = $requestApiVersion;
        $this->requestApiKey = $requestApiKey;
        $this->correlationId = $correlationId ?? ++self::$correlationIdIncrValue;
        $this->clientId = $clientId;
        $this->map = [
            new ProtocolField('requestApiKey', 'Int16', null, 0),
            new ProtocolField('requestApiVersion', 'Int16', null, 0),
            new ProtocolField('correlationId', 'Int32', null, 0),
            new ProtocolField('clientId', 'NullableString', null, 1),
        ];
    }

    public function getRequestApiVersion(): int
    {
        return $this->requestApiVersion;
    }

    public function setRequestApiVersion(int $requestApiVersion): self
    {
        $this->requestApiVersion = $requestApiVersion;

        return $this;
    }

    public function getCorrelationId(): int
    {
        return $this->correlationId;
    }

    public function setCorrelationId(int $correlationId): self
    {
        $this->correlationId = $correlationId;

        return $this;
    }

    public function getRequestApiKey(): ?int
    {
        return $this->requestApiKey;
    }

    public function setRequestApiKey(int $requestApiKey): self
    {
        $this->requestApiKey = $requestApiKey;

        return $this;
    }

    public function getClientId(): ?string
    {
        return $this->clientId;
    }

    public function setClientId(?string $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }
}
