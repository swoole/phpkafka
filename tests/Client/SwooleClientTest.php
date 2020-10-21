<?php

declare(strict_types=1);

namespace longlang\phpkafka\Test\Client;

use longlang\phpkafka\Client\ClientInterface;
use longlang\phpkafka\Client\SwooleClient;
use longlang\phpkafka\Test\TestUtil;
use Swoole\Coroutine;

class SwooleClientTest extends SyncClientTest
{
    private function checkSwoole()
    {
        if (!\extension_loaded('swoole') || -1 === Coroutine::getCid()) {
            $this->markTestSkipped();
        }
    }

    public function testClient()
    {
        $this->checkSwoole();
        $client = TestUtil::createKafkaClient(SwooleClient::class);
        $this->assertEquals(TestUtil::getHost(), $client->getHost());
        $this->assertEquals(TestUtil::getPort(), $client->getPort());

        return $client;
    }

    /**
     * @depends testClient
     *
     * @return void
     */
    public function testConnect(ClientInterface $client)
    {
        $this->checkSwoole();

        return parent::testConnect($client);
    }

    /**
     * @depends testConnect
     *
     * @return void
     */
    public function testGetApiKeys(ClientInterface $client)
    {
        $this->checkSwoole();

        return parent::testGetApiKeys($client);
    }

    /**
     * @depends testConnect
     *
     * @return void
     */
    public function testClose(ClientInterface $client)
    {
        $this->checkSwoole();

        return parent::testClose($client);
    }
}
