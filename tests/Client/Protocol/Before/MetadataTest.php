<?php

declare(strict_types=1);

namespace longlang\phpkafka\Test\Client\Protocol\Before;

use longlang\phpkafka\Client\ClientInterface;
use longlang\phpkafka\Protocol\Metadata\MetadataRequest;
use longlang\phpkafka\Protocol\Metadata\MetadataRequestTopic;
use longlang\phpkafka\Protocol\Metadata\MetadataResponse;
use longlang\phpkafka\Test\TestUtil;
use PHPUnit\Framework\TestCase;

class MetadataTest extends TestCase
{
    public function testRequest()
    {
        $client = TestUtil::createKafkaClient();
        $client->connect();
        $request = new MetadataRequest();
        $request->setTopics([
            (new MetadataRequestTopic())->setName('test'),
        ]);
        $correlationId = $client->send($request);
        $this->assertGreaterThan(0, $correlationId);

        return [$client, $correlationId];
    }

    /**
     * @depends testRequest
     *
     * @return void
     */
    public function testResponse($args)
    {
        /** @var ClientInterface $client */
        [$client, $correlationId] = $args;
        try {
            /** @var MetadataResponse $response */
            $response = $client->recv($correlationId);
            $topics = $response->getTopics();
            $this->assertCount(1, $topics);
            $this->assertEquals('test', $topics[0]->getName());
        } finally {
            $client->close();
        }
    }
}
