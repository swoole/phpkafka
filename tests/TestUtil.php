<?php

declare(strict_types=1);

namespace longlang\phpkafka\Test;

use longlang\phpkafka\Client\ClientInterface;
use longlang\phpkafka\Client\SyncClient;
use longlang\phpkafka\Config\CommonConfig;

class TestUtil
{
    private function __construct()
    {
    }

    public static function getHost(): string
    {
        return getenv('KAFKA_HOST') ?: '127.0.0.1';
    }

    public static function getPort(): int
    {
        return (int) (getenv('KAFKA_PORT') ?: 9092);
    }

    public static function createKafkaClient(): ClientInterface
    {
        $config = new CommonConfig();
        $config->setSendTimeout(10);
        $config->setRecvTimeout(10);

        return new SyncClient(self::getHost(), self::getPort(), $config);
    }
}
