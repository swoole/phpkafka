<?php

declare(strict_types=1);

namespace longlang\phpkafka\Test;

use longlang\phpkafka\Client\ClientInterface;
use longlang\phpkafka\Client\SyncClient;
use longlang\phpkafka\Config\CommonConfig;
use longlang\phpkafka\Protocol\Metadata\MetadataRequest;
use longlang\phpkafka\Protocol\Metadata\MetadataRequestTopic;
use longlang\phpkafka\Protocol\Metadata\MetadataResponse;

class TestUtil
{
    private function __construct()
    {
    }

    public static function getHost(): string
    {
        return getenv('KAFKA_HOST') ?: '127.0.0.1';
    }

    public static function getPort(): int
    {
        return (int) (getenv('KAFKA_PORT') ?: 9092);
    }

    public static function getSasl(): array
    {
        $result = getenv('KAFKA_SASL') ?: '{}';

        return json_decode($result, true);
    }

    public static function createKafkaClient(string $class = null): ClientInterface
    {
        $config = new CommonConfig();
        $config->setSendTimeout(10);
        $config->setRecvTimeout(10);
        $config->setSasl(self::getSasl());
        if (null === $class) {
            $class = getenv('KAFKA_CLIENT_CLASS') ?: SyncClient::class;
        }

        return new $class(self::getHost(), self::getPort(), $config);
    }

    public static function getControllerClient(): ClientInterface
    {
        $client = self::createKafkaClient();
        $client->connect();
        $request = new MetadataRequest();
        /** @var MetadataResponse $response */
        $response = $client->sendRecv($request);
        $client->close();

        $config = new CommonConfig();
        $config->setSendTimeout(10);
        $config->setRecvTimeout(10);
        $config->setSasl(self::getSasl());
        $class = getenv('KAFKA_CLIENT_CLASS') ?: SyncClient::class;
        $nodeId = $response->getControllerId();
        foreach ($response->getBrokers() as $broker) {
            if ($broker->getNodeId() === $nodeId) {
                return new $class($broker->getHost(), $broker->getPort(), $config);
            }
        }

        throw new \RuntimeException('getControllerClient failed');
    }

    public static function getLeaderBrokerClient(string $topic, int $partition): ClientInterface
    {
        $client = self::createKafkaClient();
        $client->connect();
        $request = new MetadataRequest();
        $topicsArray = [];
        $topicsArray[] = (new MetadataRequestTopic())->setName($topic);
        $request->setTopics($topicsArray);
        /** @var MetadataResponse $response */
        $response = $client->sendRecv($request);
        $client->close();
        foreach ($response->getTopics() as $topicItem) {
            if ($topicItem->getName() === $topic) {
                foreach ($topicItem->getPartitions() as $partitionItem) {
                    if ($partitionItem->getPartitionIndex() === $partition) {
                        $config = new CommonConfig();
                        $config->setSendTimeout(10);
                        $config->setRecvTimeout(10);
                        $config->setSasl(self::getSasl());
                        $class = getenv('KAFKA_CLIENT_CLASS') ?: SyncClient::class;
                        $nodeId = $partitionItem->getLeaderId();
                        foreach ($response->getBrokers() as $broker) {
                            if ($broker->getNodeId() === $nodeId) {
                                return new $class($broker->getHost(), $broker->getPort(), $config);
                            }
                        }
                    }
                }
            }
        }

        throw new \RuntimeException(sprintf('getLeaderBrokerClient %s-%s failed', $topic, $partition));
    }
}
