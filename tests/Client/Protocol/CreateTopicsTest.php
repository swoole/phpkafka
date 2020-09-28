<?php

declare(strict_types=1);

namespace Longyan\Kafka\Test\Client\Protocol;

use Longyan\Kafka\Client\ClientInterface;
use Longyan\Kafka\Protocol\CreateTopics\CreatableTopic;
use Longyan\Kafka\Protocol\CreateTopics\CreateTopicsRequest;
use Longyan\Kafka\Protocol\CreateTopics\CreateTopicsResponse;
use Longyan\Kafka\Protocol\ErrorCode;
use Longyan\Kafka\Test\TestUtil;
use PHPUnit\Framework\TestCase;

class CreateTopicsTest extends TestCase
{
    public function testRequest()
    {
        $client = TestUtil::createKafkaClient();
        $client->connect();
        $request = new CreateTopicsRequest();
        $request->setTopics([
            (new CreatableTopic())->setTopicName('CreateTopicsTest')->setNumPartitions(1)->setReplicationFactor(1),
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
