<?php

declare(strict_types=1);

namespace Longyan\Kafka\Test\Client;

use PHPUnit\Framework\TestCase;
use Longyan\Kafka\Client\SyncClient;
use Longyan\Kafka\Client\ClientInterface;

class SyncClientTest extends TestCase
{
    /**
     * @var string
     */
    private $host;

    /**
     * @var int
     */
    private $port;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->host = getenv('KAFKA_HOST') ?: '127.0.0.1';
        $this->port = (int)(getenv('KAFKA_PORT') ?: 9092);
    }

    public function testClient()
    {
        $client = new SyncClient($this->host, $this->port);
        $this->assertEquals($this->host, $client->getHost());
        $this->assertEquals($this->port, $client->getPort());
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
