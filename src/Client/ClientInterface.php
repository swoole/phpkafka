<?php

declare(strict_types=1);

namespace Longyan\Kafka\Client;

use Longyan\Kafka\Config\CommonConfig;
use Longyan\Kafka\Protocol\AbstractRequest;
use Longyan\Kafka\Protocol\AbstractResponse;
use Longyan\Kafka\Protocol\RequestHeader\RequestHeader;
use Longyan\Kafka\Protocol\ResponseHeader\ResponseHeader;
use Longyan\Kafka\Socket\SocketInterface;
use Longyan\Kafka\Socket\StreamSocket;

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
     * Send message to kafka server.
     *
     * If successful, return the correlationId
     */
    public function send(AbstractRequest $request, ?RequestHeader $header = null, bool $hasResponse = true): int;

    public function recv(?int $correlationId, ?ResponseHeader &$header = null): AbstractResponse;

    public function sendRecv(AbstractRequest $request, ?RequestHeader $requestHeader = null, ?ResponseHeader &$responseHeader = null): AbstractResponse;
}
