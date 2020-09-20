<?php

declare(strict_types=1);

namespace Longyan\Kafka\Test\Client;

use Longyan\Kafka\Client\ClientInterface;
use Longyan\Kafka\Test\TestUtil;
use PHPUnit\Framework\TestCase;

class SyncClientTest extends TestCase
{
    public function testClient()
    {
        $client = TestUtil::createKafkaClient();
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
        $client->connect();
        $this->assertTrue(true);

        return $client;
    }

    /**
     * @depends testConnect
     *
     * @return void
     */
    public function testGetApiKeys(ClientInterface $client)
    {
        $this->assertNotEmpty($client->getApiKeys());
    }

    /**
     * @depends testConnect
     *
     * @return void
     */
    public function testClose(ClientInterface $client)
    {
        $this->assertTrue($client->close());
    }
}
