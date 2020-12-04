<?php

declare(strict_types=1);

namespace longlang\phpkafka\Producer;

use longlang\phpkafka\Broker;
use longlang\phpkafka\Protocol\ErrorCode;
use longlang\phpkafka\Protocol\Produce\PartitionProduceData;
use longlang\phpkafka\Protocol\Produce\ProduceRequest;
use longlang\phpkafka\Protocol\Produce\ProduceResponse;
use longlang\phpkafka\Protocol\Produce\TopicProduceData;
use longlang\phpkafka\Protocol\RecordBatch\Record;
use longlang\phpkafka\Protocol\RecordBatch\RecordBatch;

class Producer
{
    /**
     * @var ProducerConfig
     */
    protected $config;

    /**
     * @var Broker
     */
    protected $broker;

    public function __construct(ProducerConfig $config)
    {
        $this->config = $config;
        $this->broker = $broker = new Broker($config);
        if ($config->getUpdateBrokers()) {
            $broker->updateBrokers();
        } else {
            $broker->setBrokers($config->getBrokers());
        }
    }

    public function send(string $topic, ?string $value, ?string $key = null, array $headers = [], int $partitionIndex = 0, ?int $brokerId = null)
    {
        $config = $this->config;
        $request = new ProduceRequest();
        $request->setAcks($acks = $config->getAcks());
        $recvTimeout = $config->getRecvTimeout();
        if ($recvTimeout < 0) {
            $request->setTimeoutMs(60000);
        } else {
            $request->setTimeoutMs((int) ($recvTimeout * 1000));
        }

        $topicData = new TopicProduceData();
        $topicData->setName($topic);
        $partition = new PartitionProduceData();
        $partition->setPartitionIndex($partitionIndex);
        $recordBatch = new RecordBatch();
        $recordBatch->setProducerId($config->getProducerId());
        $recordBatch->setProducerEpoch($config->getProducerEpoch());
        $recordBatch->setPartitionLeaderEpoch($config->getPartitionLeaderEpoch());
        $record = new Record();
        $record->setKey($key);
        $record->setValue($value);
        $record->setHeaders($headers);
        $recordBatch->setRecords([$record]);
        $timestamp = (int) (microtime(true) * 1000);
        $recordBatch->setFirstTimestamp($timestamp);
        $recordBatch->setMaxTimestamp($timestamp);
        $partition->setRecords($recordBatch);
        $topicData->setPartitions([$partition]);

        $request->setTopics([$topicData]);

        $hasResponse = 0 !== $acks;
        $client = $this->broker->getClient($brokerId);
        $correlationId = $client->send($request, null, $hasResponse);
        if (!$hasResponse) {
            return;
        }
        /** @var ProduceResponse $response */
        $response = $client->recv($correlationId);
        foreach ($response->getResponses() as $response) {
            foreach ($response->getPartitions() as $partition) {
                ErrorCode::check($partition->getErrorCode());
            }
        }
    }

    /**
     * @param ProduceMessage[] $messages
     *
     * @return void
     */
    public function sendBatch(array $messages, ?int $brokerId = null)
    {
        $config = $this->config;
        $request = new ProduceRequest();
        $request->setAcks($acks = $config->getAcks());
        $recvTimeout = $config->getRecvTimeout();
        if ($recvTimeout < 0) {
            $request->setTimeoutMs(60000);
        } else {
            $request->setTimeoutMs((int) ($recvTimeout * 1000));
        }

        $timestamp = (int) (microtime(true) * 1000);
        $topicsMap = [];
        $partitionsMap = [];
        foreach ($messages as $message) {
            $topicName = $message->getTopic();
            $partitionIndex = $message->getPartitionIndex();
            if (isset($topicsMap[$topicName])) {
                /** @var TopicProduceData $topicData */
                $topicData = $topicsMap[$topicName];
                $partitions = $topicData->getPartitions();
            } else {
                $topicData = $topicsMap[$topicName] = new TopicProduceData();
                $topicData->setName($topicName);
                $partitions = [];
            }
            if (isset($partitionsMap[$topicName][$partitionIndex])) {
                /** @var PartitionProduceData $partition */
                $partition = $partitionsMap[$topicName][$partitionIndex];
                $recordBatch = $partition->getRecords();
                $records = $recordBatch->getRecords();
            } else {
                $partition = $partitions[] = $partitionsMap[$topicName][$partitionIndex] = new PartitionProduceData();
                $partition->setPartitionIndex($partitionIndex);
                $partition->setRecords($recordBatch = new RecordBatch());
                $recordBatch->setProducerId($config->getProducerId());
                $recordBatch->setProducerEpoch($config->getProducerEpoch());
                $recordBatch->setPartitionLeaderEpoch($config->getPartitionLeaderEpoch());
                $recordBatch->setFirstTimestamp($timestamp);
                $recordBatch->setMaxTimestamp($timestamp);
                $recordBatch->setLastOffsetDelta(-1);
                $records = [];
            }
            $offsetDelta = $recordBatch->getLastOffsetDelta() + 1;
            $recordBatch->setLastOffsetDelta($offsetDelta);
            $record = $records[] = new Record();
            $record->setKey($message->getKey());
            $record->setValue($message->getValue());
            $record->setHeaders($message->getHeaders());
            $record->setOffsetDelta($offsetDelta);
            $record->setTimestampDelta(((int) (microtime(true) * 1000)) - $timestamp);
            $recordBatch->setRecords($records);

            $topicData->setPartitions($partitions);
        }
        $request->setTopics($topicsMap);

        $hasResponse = 0 !== $acks;
        $client = $this->broker->getClient($brokerId);
        $correlationId = $client->send($request, null, $hasResponse);
        if (!$hasResponse) {
            return;
        }
        /** @var ProduceResponse $response */
        $response = $client->recv($correlationId);
        foreach ($response->getResponses() as $response) {
            foreach ($response->getPartitions() as $partition) {
                ErrorCode::check($partition->getErrorCode());
            }
        }
    }

    public function close()
    {
        $this->broker->close();
    }

    public function getConfig(): ProducerConfig
    {
        return $this->config;
    }

    public function getBroker(): Broker
    {
        return $this->broker;
    }
}
