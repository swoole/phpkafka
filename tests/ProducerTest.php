<?php

declare(strict_types=1);

namespace longlang\phpkafka\Test;

use longlang\phpkafka\Producer\ProduceMessage;
use longlang\phpkafka\Producer\Producer;
use longlang\phpkafka\Producer\ProducerConfig;
use longlang\phpkafka\Protocol\RecordBatch\RecordHeader;
use PHPUnit\Framework\TestCase;

class ProducerTest extends TestCase
{
    public function testSend(): void
    {
        $config = new ProducerConfig();
        $config->setBootstrapServer(TestUtil::getHost() . ':' . TestUtil::getPort());
        TestUtil::addConfigInfo($config);
        $config->setAcks(-1);
        $producer = new Producer($config);
        $producer->send('test', (string) microtime(true), uniqid('', true), [], 0);
        $producer->send('test', (string) microtime(true), uniqid('', true));
        $producer->send('test', (string) microtime(true), null);
        // uncreated topic
        $producer->send('test_' . mt_rand(), (string) microtime(true));
        $producer->close();
        $this->assertTrue(true);
    }

    public function testSendBatch(): void
    {
        $config = new ProducerConfig();
        $config->setBootstrapServer(TestUtil::getHost() . ':' . TestUtil::getPort());
        TestUtil::addConfigInfo($config);
        $config->setAcks(-1);
        $producer = new Producer($config);
        $producer->sendBatch([
            new ProduceMessage('test', 'v1', 'k1', [], 0),
            new ProduceMessage('test', 'v2', 'k2'),
            new ProduceMessage('test', 'v3', null),
        ]);
        // uncreated topic
        $producer->sendBatch([
            new ProduceMessage('test' . mt_rand(), 'v1', 'k1', [], 0),
            new ProduceMessage('test' . mt_rand(), 'v2', 'k2'),
            new ProduceMessage('test' . mt_rand(), 'v3', null),
        ]);
        $producer->close();
        $this->assertTrue(true);
    }

    public function testSendWithHeader(): void
    {
        $config = new ProducerConfig();
        $config->setBootstrapServer(TestUtil::getHost() . ':' . TestUtil::getPort());
        TestUtil::addConfigInfo($config);
        $config->setAcks(-1);
        $producer = new Producer($config);
        $headers = [
            'key1' => 'value1',
            (new RecordHeader())->setHeaderKey('key2')->setValue('value2'),
        ];
        $producer->send('test-header', (string) microtime(true), uniqid('', true), $headers);
        $producer->close();
        $this->assertTrue(true);
    }
}
