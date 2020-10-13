<?php

declare(strict_types=1);

namespace Longyan\Kafka;

use InvalidArgumentException;
use Longyan\Kafka\Client\ClientInterface;
use Longyan\Kafka\Consumer\ConsumerConfig;
use Longyan\Kafka\Producer\ProducerConfig;
use Longyan\Kafka\Protocol\Metadata\MetadataRequest;
use Longyan\Kafka\Protocol\Metadata\MetadataResponse;

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

        $clientClass = $config->getClient();
        /** @var ClientInterface $client */
        $client = new $clientClass($url['host'], $url['port'] ?? 9092, $config, $config->getSocket());
        $client->connect();
        $request = new MetadataRequest();
        /** @var MetadataResponse $response */
        $response = $client->sendRecv($request);
        $client->close();

        $brokers = [];
        foreach ($response->getBrokers() as $broker) {
            $brokers[$broker->getNodeId()] = $broker->getHost() . ':' . $broker->getPort();
        }
        $this->setBrokers($brokers);
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
            $clientClass = $config->getClient();

            /** @var ClientInterface $client */
            $client = new $clientClass($url['host'], $url['port'] ?? 9092, $config, $config->getSocket());
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
     * @return ProducerConfig
     */
    public function getConfig()
    {
        return $this->config;
    }
}
