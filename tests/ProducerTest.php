<?php

declare(strict_types=1);

namespace longlang\phpkafka\Test;

use longlang\phpkafka\Producer\ProduceMessage;
use longlang\phpkafka\Producer\Producer;
use longlang\phpkafka\Producer\ProducerConfig;
use PHPUnit\Framework\TestCase;

class ProducerTest extends TestCase
{
    public function testSend()
    {
        $config = new ProducerConfig();
        $config->setBootstrapServer(TestUtil::getHost() . ':' . TestUtil::getPort());
        $config->setAcks(-1);
        $producer = new Producer($config);
        $producer->send('test', (string) microtime(true), uniqid('', true), [], 0);
        $producer->send('test', (string) microtime(true), uniqid('', true));
        $producer->send('test', (string) microtime(true), null);
        $producer->close();
        $this->assertTrue(true);
    }

    public function testSendBatch()
    {
        $config = new ProducerConfig();
        $config->setBootstrapServer(TestUtil::getHost() . ':' . TestUtil::getPort());
        $config->setAcks(-1);
        $producer = new Producer($config);
        $producer->sendBatch([
            new ProduceMessage('test', 'v1', 'k1', [], 0),
            new ProduceMessage('test', 'v2', 'k2'),
            new ProduceMessage('test', 'v3', null),
        ]);
        $producer->close();
        $this->assertTrue(true);
    }
}
