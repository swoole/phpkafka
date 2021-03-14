<?php

declare(strict_types=1);

namespace longlang\phpkafka\Test\Client\Protocol\After;

use longlang\phpkafka\Client\ClientInterface;
use longlang\phpkafka\Protocol\DeleteTopics\DeleteTopicsRequest;
use longlang\phpkafka\Protocol\DeleteTopics\DeleteTopicsResponse;
use longlang\phpkafka\Test\TestUtil;
use PHPUnit\Framework\TestCase;

class DeleteTopicsTest extends TestCase
{
    public function testRequest(): array
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
     */
    public function testResponse(array $args): void
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
