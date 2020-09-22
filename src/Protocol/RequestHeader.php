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
     * Optional value of the initial principal name when the request is redirected by a broker, for audit logging and quota purpose.
     *
     * @var string|null
     */
    protected $initialPrincipalName;

    /**
     * Optional value of the initial client id when the request is redirected by a broker, for quota purpose.
     *
     * @var string|null
     */
    protected $initialClientId;

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
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('requestApiKey', 'Int16', null, 0),
                new ProtocolField('requestApiVersion', 'Int16', null, 0),
                new ProtocolField('correlationId', 'Int32', null, 0),
                new ProtocolField('clientId', 'NullableString', null, 1),
            ];
            self::$taggedFieldses[self::class] = [
                new ProtocolField('initialPrincipalName', 'CompactNullableString', null, 2),
                new ProtocolField('initialClientId', 'CompactNullableString', null, 2),
            ];
        }
    }

    public function getFlexibleVersions(): ?int
    {
        return 2;
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

    public function getInitialPrincipalName(): ?string
    {
        return $this->initialPrincipalName;
    }

    public function setInitialPrincipalName(?string $initialPrincipalName): self
    {
        $this->initialPrincipalName = $initialPrincipalName;

        return $this;
    }

    public function getInitialClientId(): ?string
    {
        return $this->initialClientId;
    }

    public function setInitialClientId(?string $initialClientId): self
    {
        $this->initialClientId = $initialClientId;

        return $this;
    }

    public static function parseVersion(int $requestApiVersion, int $flexibleVersion): int
    {
        return $requestApiVersion >= $flexibleVersion ? 2 : 1;
    }
}
