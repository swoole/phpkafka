<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\RequestHeader;

use Longyan\Kafka\Protocol\AbstractRequestHeader;
use Longyan\Kafka\Protocol\ProtocolField;

class RequestHeader extends AbstractRequestHeader
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

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('requestApiKey', 'int16', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('requestApiVersion', 'int16', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('correlationId', 'int32', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('clientId', 'string', false, [1, 2], [], [1, 2], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [2];
    }

    public function getRequestApiKey(): int
    {
        return $this->requestApiKey;
    }

    public function setRequestApiKey(int $requestApiKey): self
    {
        $this->requestApiKey = $requestApiKey;

        return $this;
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
