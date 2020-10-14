<?php

declare(strict_types=1);

namespace longlang\phpkafka\Test\Client\Protocol\After;

use longlang\phpkafka\Client\ClientInterface;
use longlang\phpkafka\Protocol\ErrorCode;
use longlang\phpkafka\Protocol\Fetch\FetchableTopic;
use longlang\phpkafka\Protocol\Fetch\FetchPartition;
use longlang\phpkafka\Protocol\Fetch\FetchRequest;
use longlang\phpkafka\Protocol\Fetch\FetchResponse;
use longlang\phpkafka\Test\TestUtil;
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
