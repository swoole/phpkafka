<?php

declare(strict_types=1);

namespace longlang\phpkafka\Test\Client\Protocol\Before;

use longlang\phpkafka\Client\ClientInterface;
use longlang\phpkafka\Protocol\Produce\PartitionProduceData;
use longlang\phpkafka\Protocol\Produce\ProduceRequest;
use longlang\phpkafka\Protocol\Produce\ProduceResponse;
use longlang\phpkafka\Protocol\Produce\TopicProduceData;
use longlang\phpkafka\Protocol\RecordBatch\Record;
use longlang\phpkafka\Protocol\RecordBatch\RecordBatch;
use longlang\phpkafka\Test\TestUtil;
use PHPUnit\Framework\TestCase;

class ProduceTest extends TestCase
{
    public function testRequest()
    {
        $client = TestUtil::createKafkaClient();
        $client->connect();
        $request = new ProduceRequest();
        $request->setAcks(-1);
        $request->setTimeoutMs(10000);
        $request->setTopics([
            (new TopicProduceData())->setName('test')->setPartitions([
                (new PartitionProduceData())->setRecords(
                    (new RecordBatch())->setRecords([
                        (new Record())->setValue('v')->setKey('k'),
                    ])->setMagic(2)->setFirstTimestamp((int) (microtime(true) * 1000))->setMaxTimestamp((int) (microtime(true) * 1000))
                ),
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
            /** @var ProduceResponse $response */
            $response = $client->recv($correlationId);
            $responses = $response->getResponses();
            $this->assertCount(1, $responses);
            $this->assertEquals('test', $responses[0]->getName());
            $this->assertEquals(0, $responses[0]->getPartitions()[0]->getErrorCode());
        } finally {
            $client->close();
        }
    }
}
