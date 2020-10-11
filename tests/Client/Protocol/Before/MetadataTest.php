<?php

declare(strict_types=1);

namespace Longyan\Kafka\Test\Client\Protocol\Before;

use Longyan\Kafka\Client\ClientInterface;
use Longyan\Kafka\Protocol\Metadata\MetadataRequest;
use Longyan\Kafka\Protocol\Metadata\MetadataRequestTopic;
use Longyan\Kafka\Protocol\Metadata\MetadataResponse;
use Longyan\Kafka\Test\TestUtil;
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
