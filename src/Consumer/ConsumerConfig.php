<?php

declare(strict_types=1);

namespace longlang\phpkafka\Consumer;

use longlang\phpkafka\Config\CommonConfig;

class ConsumerConfig extends CommonConfig
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
     * @var string
     */
    protected $broker;

    /**
     * @var float|null
     */
    protected $interval = 0;

    /**
     * @var string
     */
    protected $groupId = '';

    /**
     * @var string
     */
    protected $memberId = '';

    /**
     * @var string|null
     */
    protected $groupInstanceId = '';

    /**
     * @var float
     */
    protected $sessionTimeout = 60;

    /**
     * @var float
     */
    protected $rebalanceTimeout = 60;

    /**
     * @var string
     */
    protected $topic;

    /**
     * @var int[]
     */
    protected $partitions = [0];

    /**
     * @var int
     */
    protected $replicaId = -1;

    /**
     * @var string
     */
    protected $rackId = '';

    /**
     * @var bool
     */
    protected $autoCommit = true;

    /**
     * @var int
     */
    protected $groupRetry = 5;

    /**
     * @var float
     */
    protected $groupRetrySleep = 1;

    /**
     * @var int
     */
    protected $offsetRetry = 5;

    /**
     * @var float
     */
    protected $groupHeartbeat = 3;

    public function getClient(): ?string
    {
        return $this->client;
    }

    public function setClient(?string $client): self
    {
        $this->client = $client;

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

    public function getInterval(): ?float
    {
        return $this->interval;
    }

    public function setInterval(float $interval): self
    {
        $this->interval = $interval;

        return $this;
    }

    public function getGroupId(): string
    {
        return $this->groupId;
    }

    public function setGroupId(string $groupId): self
    {
        $this->groupId = $groupId;

        return $this;
    }

    public function getTopic(): string
    {
        return $this->topic;
    }

    public function setTopic(string $topic): self
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * @return int[]
     */
    public function getPartitions(): array
    {
        return $this->partitions;
    }

    /**
     * @param int[] $partitions
     */
    public function setPartitions(array $partitions): self
    {
        $this->partitions = $partitions;

        return $this;
    }

    public function getBroker(): string
    {
        return $this->broker;
    }

    public function setBroker(string $broker): self
    {
        $this->broker = $broker;

        return $this;
    }

    public function getMemberId(): string
    {
        return $this->memberId;
    }

    public function setMemberId(string $memberId): self
    {
        $this->memberId = $memberId;

        return $this;
    }

    public function getGroupInstanceId(): ?string
    {
        return $this->groupInstanceId;
    }

    public function setGroupInstanceId($groupInstanceId): self
    {
        $this->groupInstanceId = $groupInstanceId;

        return $this;
    }

    public function getSessionTimeout(): float
    {
        return $this->sessionTimeout;
    }

    public function setSessionTimeout(float $sessionTimeout): self
    {
        $this->sessionTimeout = $sessionTimeout;

        return $this;
    }

    public function getRebalanceTimeout(): float
    {
        return $this->rebalanceTimeout;
    }

    public function setRebalanceTimeout(float $rebalanceTimeout): self
    {
        $this->rebalanceTimeout = $rebalanceTimeout;

        return $this;
    }

    public function getReplicaId(): int
    {
        return $this->replicaId;
    }

    public function setReplicaId(int $replicaId): self
    {
        $this->replicaId = $replicaId;

        return $this;
    }

    public function getRackId(): string
    {
        return $this->rackId;
    }

    public function setRackId(string $rackId): self
    {
        $this->rackId = $rackId;

        return $this;
    }

    public function getAutoCommit(): bool
    {
        return $this->autoCommit;
    }

    public function setAutoCommit(bool $autoCommit): self
    {
        $this->autoCommit = $autoCommit;

        return $this;
    }

    public function getGroupRetry(): int
    {
        return $this->groupRetry;
    }

    public function setGroupRetry(int $groupRetry): self
    {
        $this->groupRetry = $groupRetry;

        return $this;
    }

    public function getGroupRetrySleep(): float
    {
        return $this->groupRetrySleep;
    }

    public function setGroupRetrySleep(float $groupRetrySleep): self
    {
        $this->groupRetrySleep = $groupRetrySleep;

        return $this;
    }

    public function getOffsetRetry(): int
    {
        return $this->offsetRetry;
    }

    public function setOffsetRetry(int $offsetRetry): self
    {
        $this->offsetRetry = $offsetRetry;

        return $this;
    }

    public function getGroupHeartbeat(): float
    {
        return $this->groupHeartbeat;
    }

    public function setGroupHeartbeat(float $groupHeartbeat): self
    {
        $this->groupHeartbeat = $groupHeartbeat;

        return $this;
    }
}
