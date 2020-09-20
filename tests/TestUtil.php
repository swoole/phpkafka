<?php

declare(strict_types=1);

namespace Longyan\Kafka\Test;

use Longyan\Kafka\Client\ClientInterface;
use Longyan\Kafka\Client\SyncClient;
use Longyan\Kafka\Config\CommonConfig;

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
