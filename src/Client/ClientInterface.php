<?php

declare(strict_types=1);

namespace longlang\phpkafka\Client;

use longlang\phpkafka\Config\CommonConfig;
use longlang\phpkafka\Protocol\AbstractRequest;
use longlang\phpkafka\Protocol\AbstractResponse;
use longlang\phpkafka\Protocol\RequestHeader\RequestHeader;
use longlang\phpkafka\Protocol\ResponseHeader\ResponseHeader;
use longlang\phpkafka\Socket\SocketInterface;
use longlang\phpkafka\Socket\StreamSocket;

interface ClientInterface
{
    public function __construct(string $host, int $port, ?CommonConfig $config = null, string $socketClass = StreamSocket::class);

    public function getHost(): string;

    public function getPort(): int;

    public function getConfig(): CommonConfig;

    public function getSocket(): SocketInterface;

    /**
     * @param \longlang\phpkafka\Protocol\ApiVersions\ApiKeys[] $apiKeys
     */
    public function setApiKeys(array $apiKeys): self;

    /**
     * @return \longlang\phpkafka\Protocol\ApiVersions\ApiKeys[]|null
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
