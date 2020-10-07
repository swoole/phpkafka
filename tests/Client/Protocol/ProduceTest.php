<?php

declare(strict_types=1);

namespace Longyan\Kafka\Test\Client\Protocol;

use Longyan\Kafka\Client\ClientInterface;
use Longyan\Kafka\Protocol\Produce\PartitionProduceData;
use Longyan\Kafka\Protocol\Produce\ProduceRequest;
use Longyan\Kafka\Protocol\Produce\ProduceResponse;
use Longyan\Kafka\Protocol\Produce\TopicProduceData;
use Longyan\Kafka\Protocol\RecordBatch\Record;
use Longyan\Kafka\Protocol\RecordBatch\RecordBatch;
use Longyan\Kafka\Test\TestUtil;
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
