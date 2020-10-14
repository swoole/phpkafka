<?php

declare(strict_types=1);

namespace longlang\phpkafka\Test\Client\Protocol\Before;

use longlang\phpkafka\Client\ClientInterface;
use longlang\phpkafka\Protocol\CreateTopics\CreatableTopic;
use longlang\phpkafka\Protocol\CreateTopics\CreateTopicsRequest;
use longlang\phpkafka\Protocol\CreateTopics\CreateTopicsResponse;
use longlang\phpkafka\Protocol\ErrorCode;
use longlang\phpkafka\Test\TestUtil;
use PHPUnit\Framework\TestCase;

class CreateTopicsTest extends TestCase
{
    public function testRequest()
    {
        $client = TestUtil::createKafkaClient();
        $client->connect();
        $request = new CreateTopicsRequest();
        $request->setTopics([
            (new CreatableTopic())->setName('CreateTopicsTest')->setNumPartitions(1)->setReplicationFactor(1),
        ]);
        $request->setTimeoutMs(10000);
        $request->setValidateOnly(true);
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
            /** @var CreateTopicsResponse $response */
            $response = $client->recv($correlationId);
            $topics = $response->getTopics();
            $this->assertCount(1, $topics);
            $this->assertEquals(ErrorCode::NONE, $topics[0]->getErrorCode());
        } finally {
            $client->close();
        }
    }
}
