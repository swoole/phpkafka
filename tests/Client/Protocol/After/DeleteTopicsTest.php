<?php

declare(strict_types=1);

namespace Longyan\Kafka\Test\Client\Protocol\After;

use Longyan\Kafka\Client\ClientInterface;
use Longyan\Kafka\Protocol\DeleteTopics\DeleteTopicsRequest;
use Longyan\Kafka\Protocol\DeleteTopics\DeleteTopicsResponse;
use Longyan\Kafka\Test\TestUtil;
use PHPUnit\Framework\TestCase;

class DeleteTopicsTest extends TestCase
{
    public function testRequest()
    {
        $client = TestUtil::createKafkaClient();
        $client->connect();
        $request = new DeleteTopicsRequest();
        $request->setTopicNames(['CreateTopicsTest']);
        $request->setTimeoutMs(10000);
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
            /** @var DeleteTopicsResponse $response */
            $response = $client->recv($correlationId);
            $responses = $response->getResponses();
            $this->assertCount(1, $responses);
            $this->assertEquals('CreateTopicsTest', $responses[0]->getName());
        } finally {
            $client->close();
        }
    }
}
