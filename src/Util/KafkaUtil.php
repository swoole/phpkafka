<?php

declare(strict_types=1);

namespace longlang\phpkafka\Util;

use longlang\phpkafka\Client\SwooleClient;
use longlang\phpkafka\Client\SyncClient;
use longlang\phpkafka\Socket\StreamSocket;
use longlang\phpkafka\Socket\SwooleSocket;
use Swoole\Coroutine;

class KafkaUtil
{
    public static function getClientClass(?string $clientClass = null): string
    {
        if (null !== $clientClass) {
            return $clientClass;
        }
        if (method_exists(Coroutine::class, 'getCid') && -1 !== Coroutine::getCid()) {
            return SwooleClient::class;
        } else {
            return SyncClient::class;
        }
    }

    public static function getSocketClass(?string $socketClass = null): string
    {
        if (null !== $socketClass) {
            return $socketClass;
        }
        if (method_exists(Coroutine::class, 'getCid') && -1 !== Coroutine::getCid()) {
            return SwooleSocket::class;
        } else {
            return StreamSocket::class;
        }
    }
}
