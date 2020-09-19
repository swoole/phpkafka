<?php

declare(strict_types=1);

namespace Longyan\Kafka\Client;

use Longyan\Kafka\Config\CommonConfig;
use Longyan\Kafka\Socket\StreamSocket;
use Longyan\Kafka\Protocol\RequestHeader;
use Longyan\Kafka\Socket\SocketInterface;
use Longyan\Kafka\Protocol\ResponseHeader;
use Longyan\Kafka\Protocol\AbstractRequest;
use Longyan\Kafka\Protocol\AbstractResponse;

interface ClientInterface
{
    public function __construct(string $host, int $port, ?CommonConfig $config = null, string $socketClass = StreamSocket::class);

    public function getHost(): string;

    public function getPort(): int;

    public function getConfig(): CommonConfig;

    public function getSocket(): SocketInterface;

    /**
     * @param \Longyan\Kafka\Protocol\ApiVersions\ApiKeys[] $apiKeys
     */
    public function setApiKeys(array $apiKeys): self;

    /**
     * @return \Longyan\Kafka\Protocol\ApiVersions\ApiKeys[]|null
     */
    public function getApiKeys(): ?array;

    public function connect(): void;

    public function close(): bool;

    public function getRequestApiVersion(AbstractRequest $request): int;

    /**
     * Send message to kafka server
     * 
     * If successful, return the correlationId
     *
     * @param \Longyan\Kafka\Protocol\AbstractRequest $request
     * @param \Longyan\Kafka\Protocol\RequestHeader|null $header
     * @return integer
     */
    public function send(AbstractRequest $request, ?RequestHeader $header = null): int;

    public function recv(?int $correlationId, ?ResponseHeader &$header = null): AbstractResponse;
}
