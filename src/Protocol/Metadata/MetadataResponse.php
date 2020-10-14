<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\Metadata;

use longlang\phpkafka\Protocol\AbstractResponse;
use longlang\phpkafka\Protocol\ProtocolField;

class MetadataResponse extends AbstractResponse
{
    /**
     * The duration in milliseconds for which the request was throttled due to a quota violation, or zero if the request did not violate any quota.
     *
     * @var int
     */
    protected $throttleTimeMs = 0;

    /**
     * Each broker in the response.
     *
     * @var MetadataResponseBroker[]
     */
    protected $brokers = [];

    /**
     * The cluster ID that responding broker belongs to.
     *
     * @var string|null
     */
    protected $clusterId = null;

    /**
     * The ID of the controller broker.
     *
     * @var int
     */
    protected $controllerId = -1;

    /**
     * Each topic in the response.
     *
     * @var MetadataResponseTopic[]
     */
    protected $topics = [];

    /**
     * 32-bit bitfield to represent authorized operations for this cluster.
     *
     * @var int
     */
    protected $clusterAuthorizedOperations = -2147483648;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('throttleTimeMs', 'int32', false, [3, 4, 5, 6, 7, 8, 9], [9], [], [], null),
                new ProtocolField('brokers', MetadataResponseBroker::class, true, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9], [9], [], [], null),
                new ProtocolField('clusterId', 'string', false, [2, 3, 4, 5, 6, 7, 8, 9], [9], [2, 3, 4, 5, 6, 7, 8, 9], [], null),
                new ProtocolField('controllerId', 'int32', false, [1, 2, 3, 4, 5, 6, 7, 8, 9], [9], [], [], null),
                new ProtocolField('topics', MetadataResponseTopic::class, true, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9], [9], [], [], null),
                new ProtocolField('clusterAuthorizedOperations', 'int32', false, [8, 9], [9], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 3;
    }

    public function getFlexibleVersions(): array
    {
        return [9];
    }

    public function getThrottleTimeMs(): int
    {
        return $this->throttleTimeMs;
    }

    public function setThrottleTimeMs(int $throttleTimeMs): self
    {
        $this->throttleTimeMs = $throttleTimeMs;

        return $this;
    }

    /**
     * @return MetadataResponseBroker[]
     */
    public function getBrokers(): array
    {
        return $this->brokers;
    }

    /**
     * @param MetadataResponseBroker[] $brokers
     */
    public function setBrokers(array $brokers): self
    {
        $this->brokers = $brokers;

        return $this;
    }

    public function getClusterId(): ?string
    {
        return $this->clusterId;
    }

    public function setClusterId(?string $clusterId): self
    {
        $this->clusterId = $clusterId;

        return $this;
    }

    public function getControllerId(): int
    {
        return $this->controllerId;
    }

    public function setControllerId(int $controllerId): self
    {
        $this->controllerId = $controllerId;

        return $this;
    }

    /**
     * @return MetadataResponseTopic[]
     */
    public function getTopics(): array
    {
        return $this->topics;
    }

    /**
     * @param MetadataResponseTopic[] $topics
     */
    public function setTopics(array $topics): self
    {
        $this->topics = $topics;

        return $this;
    }

    public function getClusterAuthorizedOperations(): int
    {
        return $this->clusterAuthorizedOperations;
    }

    public function setClusterAuthorizedOperations(int $clusterAuthorizedOperations): self
    {
        $this->clusterAuthorizedOperations = $clusterAuthorizedOperations;

        return $this;
    }
}
