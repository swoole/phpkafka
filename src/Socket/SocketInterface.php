<?php

declare(strict_types=1);

namespace longlang\phpkafka\Socket;

use longlang\phpkafka\Config\CommonConfig;

interface SocketInterface
{
    public function __construct(string $host, int $port, ?CommonConfig $config = null);

    public function getHost(): string;

    public function getPort(): int;

    public function getConfig(): CommonConfig;

    public function connect(): void;

    public function close(): bool;

    public function send(string $data): int;

    public function recv(int $length): string;
}
