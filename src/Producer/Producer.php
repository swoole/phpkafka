<?php

declare(strict_types=1);

namespace longlang\phpkafka\Producer;

use longlang\phpkafka\Broker;
use longlang\phpkafka\Producer\Partitioner\PartitionerInterface;
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

    /**
     * @var PartitionerInterface
     */
    protected $partitioner;

    public function __construct(ProducerConfig $config)
    {
        $this->config = $config;
        $this->broker = $broker = new Broker($config);
        if ($config->getUpdateBrokers()) {
            $broker->updateBrokers();
        } else {
            $broker->setBrokers($config->getBrokers());
        }
        $class = $config->getPartitioner();
        $this->partitioner = new $class();
    }

    public function send(string $topic, ?string $value, ?string $key = null, array $headers = [], ?int $partitionIndex = null, ?int $brokerId = null): void
    {
        $config = $this->config;
        $broker = $this->broker;
        if (null === $partitionIndex) {
            $partitionIndex = $this->partitioner->partition($topic, $value, $key, $broker->getTopicsMeta($topic));
        }
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
        $client = $broker->getClient($brokerId ?? $broker->getBrokerIdByTopic($topic, $partitionIndex));
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
     */
    public function sendBatch(array $messages, ?int $brokerId = null): void
    {
        $config = $this->config;
        $broker = $this->broker;
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
        $topics = [];
        foreach ($messages as $message) {
            $topics[] = $message->getTopic();
        }
        $topicsMeta = $broker->getTopicsMeta($topics);
        foreach ($messages as $message) {
            $topicName = $message->getTopic();
            $value = $message->getValue();
            $key = $message->getKey();
            $partitionIndex = $message->getPartitionIndex() ?? $this->partitioner->partition($topicName, $value, $key, $topicsMeta);
            $brokerId = $broker->getBrokerIdByTopic($topicName, $partitionIndex);
            if (isset($topicsMap[$brokerId][$topicName])) {
                /** @var TopicProduceData $topicData */
                $topicData = $topicsMap[$brokerId][$topicName];
                $partitions = $topicData->getPartitions();
            } else {
                $topicData = $topicsMap[$brokerId][$topicName] = new TopicProduceData();
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
            $record->setKey($key);
            $record->setValue($value);
            $record->setHeaders($message->getHeaders());
            $record->setOffsetDelta($offsetDelta);
            $record->setTimestampDelta(((int) (microtime(true) * 1000)) - $timestamp);
            $recordBatch->setRecords($records);

            $topicData->setPartitions($partitions);
        }
        foreach ($topicsMap as $brokerId => $topics) {
            $request->setTopics($topics);

            $hasResponse = 0 !== $acks;
            $client = $broker->getClient($brokerId);
            $correlationId = $client->send($request, null, $hasResponse);
            if (!$hasResponse) {
                continue;
            }
            /** @var ProduceResponse $response */
            $response = $client->recv($correlationId);
            foreach ($response->getResponses() as $response) {
                foreach ($response->getPartitions() as $partition) {
                    ErrorCode::check($partition->getErrorCode());
                }
            }
        }
    }

    public function close(): void
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
