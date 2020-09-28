<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\CreateTopics;

use Longyan\Kafka\Protocol\AbstractStruct;
use Longyan\Kafka\Protocol\ProtocolField;

class CreatableTopic extends AbstractStruct
{
    /**
     * The topic name.
     *
     * @var string
     */
    protected $topicName;

    /**
     * The number of partitions to create in the topic, or -1 if we are either specifying a manual partition assignment or using the default partitions.
     *
     * @var int
     */
    protected $numPartitions;

    /**
     * The number of replicas to create for each partition in the topic, or -1 if we are either specifying a manual partition assignment or using the default replication factor.
     *
     * @var int
     */
    protected $replicationFactor;

    /**
     * The manual partition assignment, or the empty array if we are using automatic assignment.
     *
     * @var CreatableReplicaAssignment[]
     */
    protected $assignments = [];

    /**
     * The custom topic configurations to set.
     *
     * @var CreateableTopicConfig[]
     */
    protected $configs = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('topicName', 'string', false, [0, 1, 2, 3, 4, 5], [5], [], [], null),
                new ProtocolField('numPartitions', 'int32', false, [0, 1, 2, 3, 4, 5], [5], [], [], null),
                new ProtocolField('replicationFactor', 'int16', false, [0, 1, 2, 3, 4, 5], [5], [], [], null),
                new ProtocolField('assignments', CreatableReplicaAssignment::class, true, [0, 1, 2, 3, 4, 5], [5], [], [], null),
                new ProtocolField('configs', CreateableTopicConfig::class, true, [0, 1, 2, 3, 4, 5], [5], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [5];
    }

    public function getTopicName(): string
    {
        return $this->topicName;
    }

    public function setTopicName(string $topicName): self
    {
        $this->topicName = $topicName;

        return $this;
    }

    public function getNumPartitions(): int
    {
        return $this->numPartitions;
    }

    public function setNumPartitions(int $numPartitions): self
    {
        $this->numPartitions = $numPartitions;

        return $this;
    }

    public function getReplicationFactor(): int
    {
        return $this->replicationFactor;
    }

    public function setReplicationFactor(int $replicationFactor): self
    {
        $this->replicationFactor = $replicationFactor;

        return $this;
    }

    /**
     * @return CreatableReplicaAssignment[]
     */
    public function getAssignments(): array
    {
        return $this->assignments;
    }

    /**
     * @param CreatableReplicaAssignment[] $assignments
     */
    public function setAssignments(array $assignments): self
    {
        $this->assignments = $assignments;

        return $this;
    }

    /**
     * @return CreateableTopicConfig[]
     */
    public function getConfigs(): array
    {
        return $this->configs;
    }

    /**
     * @param CreateableTopicConfig[] $configs
     */
    public function setConfigs(array $configs): self
    {
        $this->configs = $configs;

        return $this;
    }
}
