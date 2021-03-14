<?php

declare(strict_types=1);

namespace longlang\phpkafka\Test\Client;

use Exception;
use longlang\phpkafka\Client\ClientInterface;
use longlang\phpkafka\Client\SwooleClient;
use longlang\phpkafka\Test\TestUtil;
use Swoole\Coroutine;

class SwooleClientTest extends SyncClientTest
{
    private function checkSwoole(): void
    {
        if (!\extension_loaded('swoole') || -1 === Coroutine::getCid()) {
            $this->markTestSkipped();
        }
    }

    public function testClient(): ClientInterface
    {
        $this->checkSwoole();
        $client = TestUtil::createKafkaClient(SwooleClient::class);
        $this->assertEquals(TestUtil::getHost(), $client->getHost());
        $this->assertEquals(TestUtil::getPort(), $client->getPort());

        return $client;
    }

    /**
     * @depends testClient
     */
    public function testConnect(ClientInterface $client): ClientInterface
    {
        $this->checkSwoole();

        return parent::testConnect($client);
    }

    /**
     * @depends testConnect
     */
    public function testGetApiKeys(ClientInterface $client): void
    {
        $this->checkSwoole();

        parent::testGetApiKeys($client);
    }

    /**
     * @depends testConnect
     */
    public function testClose(ClientInterface $client): void
    {
        $this->checkSwoole();

        parent::testClose($client);
    }

    public function testExceptionCallback(): void
    {
        $client = $this->testClient();
        $exception = null;
        $client->getConfig()->setExceptionCallback(function (Exception $e) use (&$exception) {
            $exception = $e;
        });
        $client->close();
        $this->assertInstanceOf(Exception::class, $exception);
    }
}
