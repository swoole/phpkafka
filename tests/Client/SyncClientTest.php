<?php

declare(strict_types=1);

namespace longlang\phpkafka\Test\Client;

use longlang\phpkafka\Client\ClientInterface;
use longlang\phpkafka\Client\SyncClient;
use longlang\phpkafka\Test\TestUtil;
use PHPUnit\Framework\TestCase;

class SyncClientTest extends TestCase
{
    public function testClient(): ClientInterface
    {
        $client = TestUtil::createKafkaClient(SyncClient::class);
        $this->assertEquals(TestUtil::getHost(), $client->getHost());
        $this->assertEquals(TestUtil::getPort(), $client->getPort());

        return $client;
    }

    /**
     * @depends testClient
     */
    public function testConnect(ClientInterface $client): ClientInterface
    {
        $client->connect();
        $this->assertTrue(true);

        return $client;
    }

    /**
     * @depends testConnect
     */
    public function testGetApiKeys(ClientInterface $client): void
    {
        $this->assertNotEmpty($client->getApiKeys());
    }

    /**
     * @depends testConnect
     */
    public function testClose(ClientInterface $client): void
    {
        $this->assertTrue($client->close());
    }
}
