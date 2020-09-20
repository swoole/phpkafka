<?php

declare(strict_types=1);

namespace Longyan\Kafka\Client;

use InvalidArgumentException;
use Longyan\Kafka\Config\CommonConfig;
use Longyan\Kafka\Exception\UnsupportedApiKeyException;
use Longyan\Kafka\Exception\UnsupportedApiVersionException;
use Longyan\Kafka\Protocol\AbstractRequest;
use Longyan\Kafka\Protocol\AbstractResponse;
use Longyan\Kafka\Protocol\ApiKeys;
use Longyan\Kafka\Protocol\ApiVersions\ApiVersionsRequest;
use Longyan\Kafka\Protocol\ApiVersions\ApiVersionsResponse;
use Longyan\Kafka\Protocol\ErrorCode;
use Longyan\Kafka\Protocol\KafkaRequest;
use Longyan\Kafka\Protocol\RequestHeader;
use Longyan\Kafka\Protocol\ResponseHeader;
use Longyan\Kafka\Protocol\Type\Int32;
use Longyan\Kafka\Socket\SocketInterface;
use Longyan\Kafka\Socket\StreamSocket;

class SyncClient implements ClientInterface
{
    /**
     * @var SocketInterface
     */
    private $socket;

    /**
     * @var \Longyan\Kafka\Protocol\ApiVersions\ApiKeys[]
     */
    private $apiKeys;

    /**
     * @var array
     */
    private $waitResponseMaps;

    public function __construct(string $host, int $port, ?CommonConfig $config = null, string $socketClass = StreamSocket::class)
    {
        $this->socket = new $socketClass($host, $port, $config);
    }

    public function getHost(): string
    {
        return $this->socket->getHost();
    }

    public function getPort(): int
    {
        return $this->socket->getPort();
    }

    public function getConfig(): CommonConfig
    {
        return $this->socket->getConfig();
    }

    public function getSocket(): SocketInterface
    {
        return $this->socket;
    }

    /**
     * @param \Longyan\Kafka\Protocol\ApiVersions\ApiKeys[] $apiKeys
     */
    public function setApiKeys(array $apiKeys): ClientInterface
    {
        $result = [];
        foreach ($apiKeys as $item) {
            $result[$item->getApiKey()] = $item;
        }
        $this->apiKeys = $result;

        return $this;
    }

    /**
     * @return \Longyan\Kafka\Protocol\ApiVersions\ApiKeys[]|null
     */
    public function getApiKeys(): ?array
    {
        return $this->apiKeys;
    }

    public function connect(): void
    {
        $this->socket->connect();
        $this->waitResponseMaps = [];
        $this->updateApiVersions();
    }

    public function close(): bool
    {
        return $this->socket->close();
    }

    public function getRequestApiVersion(AbstractRequest $request): int
    {
        $apiKey = $request->getRequestApiKey();
        $apiKeyConfig = $this->apiKeys[$apiKey] ?? null;
        if (!$apiKeyConfig) {
            if (ApiKeys::PROTOCOL_API_VERSIONS === $apiKey) {
                return 1;
            }
            throw new UnsupportedApiKeyException(sprintf('Unsupported apikey %s', $apiKey));
        }
        $requestMaxSupportedVersion = $request->getMaxSupportedVersion();
        if ($apiKeyConfig->getMinVersion() > $request->getMaxSupportedVersion()) {
            throw new UnsupportedApiVersionException(sprintf('The version of Api %s must be >= v%s and <= v%s, the latest version currently supported is v%s', $request->getApiKeyText(), $apiKeyConfig->getMinVersion(), $apiKeyConfig->getMaxVersion(), $request->getMaxSupportedVersion()));
        }
        $apiKeyMaxVersion = $apiKeyConfig->getMaxVersion();
        if ($requestMaxSupportedVersion <= $apiKeyMaxVersion) {
            return $requestMaxSupportedVersion;
        } else {
            return $apiKeyMaxVersion;
        }
    }

    /**
     * Send message to kafka server.
     *
     * If successful, return the correlationId
     */
    public function send(AbstractRequest $request, ?RequestHeader $header = null): int
    {
        $apiKey = $request->getRequestApiKey();
        if (null === $header) {
            $header = new RequestHeader($apiKey, $this->getRequestApiVersion($request), null, $this->getConfig()->getClientId());
        }
        $kafkaRequest = new KafkaRequest($request, $header);
        $this->socket->send($kafkaRequest->pack());
        $correlationId = $header->getCorrelationId();
        $this->waitResponseMaps[$correlationId] = [
            'apiKey' => $apiKey,
        ];

        return $correlationId;
    }

    public function recv(?int $correlationId, ?ResponseHeader &$header = null): AbstractResponse
    {
        $data = $this->socket->recv(4);
        $length = Int32::unpack($data);
        $data = $this->socket->recv($length);
        $header = new ResponseHeader();
        $header->unpack($data, $size);
        if (!isset($this->waitResponseMaps[$correlationId])) {
            throw new InvalidArgumentException(sprintf('Invalid correlationId %s', $correlationId));
        }
        $data = substr($data, $size);

        return ApiKeys::createResponse($this->waitResponseMaps[$correlationId]['apiKey'], $data);
    }

    protected function updateApiVersions()
    {
        $request = new ApiVersionsRequest();
        $correlationId = $this->send($request);
        /** @var ApiVersionsResponse $response */
        $response = $this->recv($correlationId);
        ErrorCode::check($response->getErrorCode());
        $this->setApiKeys($response->getApiKeys());
    }
}
