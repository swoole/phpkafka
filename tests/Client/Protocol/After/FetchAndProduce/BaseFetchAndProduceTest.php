<?php

declare(strict_types=1);

namespace longlang\phpkafka\Test\Client\Protocol\After\FetchAndProduce;

use longlang\phpkafka\Client\ClientInterface;
use longlang\phpkafka\Protocol\ErrorCode;
use longlang\phpkafka\Protocol\Fetch\FetchableTopic;
use longlang\phpkafka\Protocol\Fetch\FetchPartition;
use longlang\phpkafka\Protocol\Fetch\FetchRequest;
use longlang\phpkafka\Protocol\Fetch\FetchResponse;
use longlang\phpkafka\Protocol\Produce\PartitionProduceData;
use longlang\phpkafka\Protocol\Produce\ProduceRequest;
use longlang\phpkafka\Protocol\Produce\ProduceResponse;
use longlang\phpkafka\Protocol\Produce\TopicProduceData;
use longlang\phpkafka\Protocol\RecordBatch\Record;
use longlang\phpkafka\Protocol\RecordBatch\RecordBatch;
use longlang\phpkafka\Test\TestUtil;
use PHPUnit\Framework\TestCase;

abstract class BaseFetchAndProduceTest extends TestCase
{
    abstract public function getComporession();

    abstract public function checkSkip();

    public function testProduceRequest()
    {
        $this->checkSkip();
        $client = TestUtil::createKafkaClient();
        $client->connect();
        $request = new ProduceRequest();
        $request->setAcks(-1);
        $request->setTimeoutMs(10000);
        $recordBatch = (new RecordBatch())->setRecords([
            (new Record())->setValue('v-none-' . $this->getComporession())->setKey('k-none-' . $this->getComporession()),
        ])->setFirstTimestamp((int) (microtime(true) * 1000))->setMaxTimestamp((int) (microtime(true) * 1000));
        $recordBatch->getAttributes()->setCompression($this->getComporession());
        $recordBatch->setProducerId(-1);
        $recordBatch->setProducerEpoch(-1);
        $request->setTopics([
            (new TopicProduceData())->setName('test')->setPartitions([
                (new PartitionProduceData())->setRecords($recordBatch),
            ]),
        ]);
        $correlationId = $client->send($request);
        $this->assertGreaterThan(0, $correlationId);

        return [$client, $correlationId];
    }

    /**
     * @depends testProduceRequest
     *
     * @return void
     */
    public function testProduceResponse($args)
    {
        $this->checkSkip();
        /** @var ClientInterface $client */
        [$client, $correlationId] = $args;
        try {
            /** @var ProduceResponse $response */
            $response = $client->recv($correlationId);
            $responses = $response->getResponses();
            $this->assertCount(1, $responses);
            $this->assertEquals('test', $responses[0]->getName());
            $this->assertEquals(0, $responses[0]->getPartitions()[0]->getErrorCode());

            return [$responses[0]->getPartitions()[0]->getBaseOffset()];
        } finally {
            $client->close();
        }
    }

    /**
     * @depends testProduceResponse
     *
     * @return void
     */
    public function testFetchRequest($args)
    {
        $this->checkSkip();
        [$offset] = $args;
        $client = TestUtil::createKafkaClient();
        $client->connect();
        $request = new FetchRequest();
        $request->setReplicaId(-1);
        $request->setMaxWait(10000);
        $request->setTopics([
            (new FetchableTopic())->setName('test')->setFetchPartitions([
                (new FetchPartition())->setFetchOffset($offset),
            ]),
        ]);
        $correlationId = $client->send($request);
        $this->assertGreaterThan(0, $correlationId);

        return [$client, $correlationId];
    }

    /**
     * @depends testFetchRequest
     *
     * @return void
     */
    public function testFetchResponse($args)
    {
        $this->checkSkip();
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
            $recordBatch = $partition->getRecords();
            $records = $recordBatch->getRecords();
            $this->assertCount(1, $records);
            $this->assertEquals('k-none-' . $this->getComporession(), $records[0]->getKey());
            $this->assertEquals('v-none-' . $this->getComporession(), $records[0]->getValue());
            $this->assertEquals($this->getComporession(), $recordBatch->getAttributes()->getCompression());
        } finally {
            $client->close();
        }
    }
}
