<?php

declare(strict_types=1);

namespace longlang\phpkafka;

use InvalidArgumentException;
use longlang\phpkafka\Client\ClientInterface;
use longlang\phpkafka\Consumer\ConsumerConfig;
use longlang\phpkafka\Producer\ProducerConfig;
use longlang\phpkafka\Protocol\Metadata\MetadataRequest;
use longlang\phpkafka\Protocol\Metadata\MetadataRequestTopic;
use longlang\phpkafka\Protocol\Metadata\MetadataResponse;
use longlang\phpkafka\Protocol\Metadata\MetadataResponseTopic;
use longlang\phpkafka\Util\KafkaUtil;

class Broker
{
    /**
     * @var ProducerConfig|ConsumerConfig
     */
    protected $config;

    /**
     * @var string[]
     */
    protected $brokers;

    /**
     * @var ClientInterface[]
     */
    protected $clients = [];

    /**
     * @var MetadataResponseTopic[]
     */
    protected $topicsMeta;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function close()
    {
        foreach ($this->clients as $client) {
            $client->close();
        }
        $this->clients = [];
    }

    public function updateBrokers()
    {
        $config = $this->config;

        $url = null;
        if ($config instanceof ConsumerConfig) {
            $url = parse_url(explode(',', $config->getBroker())[0]);
        }
        if (!$url) {
            $bootstrapServers = $config->getBootstrapServers();
            $url = parse_url($bootstrapServers[array_rand($bootstrapServers)]);
        }

        if (!$url) {
            throw new InvalidArgumentException(sprintf('Invalid bootstrapServer'));
        }

        $clientClass = KafkaUtil::getClientClass($config->getClient());
        /** @var ClientInterface $client */
        $client = new $clientClass($url['host'], $url['port'] ?? 9092, $config, KafkaUtil::getSocketClass($config->getSocket()));
        $client->connect();
        $response = $this->updateMetadata([], $client);
        $client->close();

        $brokers = [];
        foreach ($response->getBrokers() as $broker) {
            $brokers[$broker->getNodeId()] = $broker->getHost() . ':' . $broker->getPort();
        }
        $this->setBrokers($brokers);
    }

    public function updateMetadata(array $topics = [], ?ClientInterface $client = null): MetadataResponse
    {
        if (null === $client) {
            $client = $this->getClient();
        }
        $config = $this->config;
        $request = new MetadataRequest();
        $topicsArray = [];
        foreach ($topics as $topic) {
            $topicsArray[] = (new MetadataRequestTopic())->setName($topic);
        }
        $request->setTopics($topicsArray ?: null);
        $request->setAllowAutoTopicCreation($config->getAutoCreateTopic());
        /** @var MetadataResponse $response */
        $response = $client->sendRecv($request);
        $this->topicsMeta = $response->getTopics();

        return $response;
    }

    public function getClient(?int $brokerId = null): ClientInterface
    {
        return $this->getClientByBrokerId($brokerId ?? array_rand($this->brokers, 1));
    }

    public function getClientByBrokerId(int $brokerId): ClientInterface
    {
        if (!isset($this->brokers[$brokerId])) {
            throw new InvalidArgumentException(sprintf('Not found brokerId %s', $brokerId));
        }

        $url = parse_url($this->brokers[$brokerId]);
        if (!$url) {
            throw new InvalidArgumentException(sprintf('Invalid bootstrapServer %s', $this->brokers[$brokerId]));
        }

        $config = $this->config;
        if (!isset($this->clients[$brokerId])) {
            $clientClass = KafkaUtil::getClientClass($config->getClient());

            /** @var ClientInterface $client */
            $client = new $clientClass($url['host'], $url['port'] ?? 9092, $config, KafkaUtil::getSocketClass($config->getSocket()));
            $client->connect();
            $this->clients[$brokerId] = $client;
        }

        return $this->clients[$brokerId];
    }

    /**
     * @return string|string[]
     */
    public function getBrokers()
    {
        return $this->brokers;
    }

    /**
     * @param string|string[] $brokers
     */
    public function setBrokers($brokers): self
    {
        if (\is_string($brokers)) {
            $this->brokers = explode(',', $brokers);
        } elseif (\is_array($brokers)) {
            $this->brokers = $brokers;
        } else {
            throw new InvalidArgumentException(sprintf('The type of brokers must be string or array, and the current type is %', \gettype($brokers)));
        }

        return $this;
    }

    /**
     * @return @var ProducerConfig|ConsumerConfig
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return MetadataResponseTopic[]
     */
    public function getTopicsMeta(): array
    {
        return $this->topicsMeta;
    }
}
