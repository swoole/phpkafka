<?php

declare(strict_types=1);

namespace longlang\phpkafka\Producer;

use longlang\phpkafka\Config\CommonConfig;

class ProducerConfig extends CommonConfig
{
    /**
     * Client class.
     *
     * @var string|null
     */
    protected $client;

    /**
     * Socket class.
     *
     * @var string|null
     */
    protected $socket;

    /**
     * @var string|string[]
     */
    protected $brokers;

    /**
     * @var string
     */
    protected $bootstrapServer;

    /**
     * Auto update brokers.
     *
     * @var bool
     */
    protected $updateBrokers = true;

    /**
     * The number of acknowledgments the producer requires the leader to have received before considering a request complete. Allowed values: 0 for no acknowledgments, 1 for only the leader and -1 for the full ISR.
     *
     * @var int
     */
    protected $acks = 0;

    /**
     * @var int
     */
    protected $producerId = -1;

    /**
     * @var int
     */
    protected $producerEpoch = -1;

    /**
     * @var int
     */
    protected $partitionLeaderEpoch = -1;

    public function getClient(): ?string
    {
        return $this->client;
    }

    public function setClient(?string $client): self
    {
        $this->client = $client;

        return $this;
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
        $this->brokers = $brokers;

        return $this;
    }

    public function getBootstrapServer(): string
    {
        return $this->bootstrapServer;
    }

    public function setBootstrapServer(string $bootstrapServer): self
    {
        $this->bootstrapServer = $bootstrapServer;

        return $this;
    }

    public function getSocket(): ?string
    {
        return $this->socket;
    }

    public function setSocket(?string $socket): self
    {
        $this->socket = $socket;

        return $this;
    }

    public function getUpdateBrokers(): bool
    {
        return $this->updateBrokers;
    }

    public function setUpdateBrokers(bool $updateBrokers): self
    {
        $this->updateBrokers = $updateBrokers;

        return $this;
    }

    public function getAcks(): int
    {
        return $this->acks;
    }

    public function setAcks(int $acks): self
    {
        $this->acks = $acks;

        return $this;
    }

    public function getProducerId(): int
    {
        return $this->producerId;
    }

    public function setProducerId(int $producerId): self
    {
        $this->producerId = $producerId;

        return $this;
    }

    public function getProducerEpoch(): int
    {
        return $this->producerEpoch;
    }

    public function setProducerEpoch(int $producerEpoch): self
    {
        $this->producerEpoch = $producerEpoch;

        return $this;
    }

    public function getPartitionLeaderEpoch(): int
    {
        return $this->partitionLeaderEpoch;
    }

    public function setPartitionLeaderEpoch(int $partitionLeaderEpoch): self
    {
        $this->partitionLeaderEpoch = $partitionLeaderEpoch;

        return $this;
    }
}
