<?php

declare(strict_types=1);

namespace Longyan\Kafka\Test\Client\Protocol\After;

use Longyan\Kafka\Client\ClientInterface;
use Longyan\Kafka\Protocol\ErrorCode;
use Longyan\Kafka\Protocol\Fetch\FetchableTopic;
use Longyan\Kafka\Protocol\Fetch\FetchPartition;
use Longyan\Kafka\Protocol\Fetch\FetchRequest;
use Longyan\Kafka\Protocol\Fetch\FetchResponse;
use Longyan\Kafka\Test\TestUtil;
use PHPUnit\Framework\TestCase;

class FetchTest extends TestCase
{
    public function testRequest()
    {
        $client = TestUtil::createKafkaClient();
        $client->connect();
        $request = new FetchRequest();
        $request->setReplicaId(-1);
        $request->setMaxWait(10000);
        $request->setTopics([
            (new FetchableTopic())->setName('test')->setFetchPartitions([
                (new FetchPartition())->setFetchOffset(0),
            ]),
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
            /** @var FetchResponse $response */
            $response = $client->recv($correlationId);
            $this->assertEquals(ErrorCode::NONE, $response->getErrorCode());
            $topics = $response->getTopics();
            $this->assertCount(1, $topics);
            $topic = $topics[0];
            $this->assertEquals('test', $topic->getName());
            $partition = $topic->getPartitions()[0];
            $this->assertEquals(ErrorCode::NONE, $partition->getErrorCode());
            $records = $partition->getRecords()->getRecords();
            $this->assertCount(1, $records);
            $this->assertEquals('k', $records[0]->getKey());
            $this->assertEquals('v', $records[0]->getValue());
        } finally {
            $client->close();
        }
    }
}
