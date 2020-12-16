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
        $url = parse_url($config->getBootstrapServer());
        if (!$url) {
            throw new InvalidArgumentException(sprintf('Invalid bootstrapServer %s', $config->getBootstrapServer()));
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
        if (null === $brokerId) {
            return $this->getRandomClient();
        } elseif (isset($this->brokers[$brokerId])) {
            return $this->brokers[$brokerId];
        } else {
            throw new InvalidArgumentException(sprintf('Not found brokerId %s', $brokerId));
        }
    }

    public function getRandomClient(): ClientInterface
    {
        $brokers = $this->getBrokers();
        $index = array_rand($brokers, 1);
        $url = parse_url($brokers[$index]);
        if (!$url) {
            throw new InvalidArgumentException(sprintf('Invalid bootstrapServer %s', $brokers[$index]));
        }
        $config = $this->config;
        if (!isset($this->clients[$index])) {
            $clientClass = KafkaUtil::getClientClass($config->getClient());

            /** @var ClientInterface $client */
            $client = new $clientClass($url['host'], $url['port'] ?? 9092, $config, KafkaUtil::getSocketClass($config->getSocket()));
            $client->connect();
            $this->clients[$index] = $client;
        }

        return $this->clients[$index];
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
