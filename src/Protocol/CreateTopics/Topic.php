<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\CreateTopics;

use Longyan\Kafka\Protocol\AbstractStruct;
use Longyan\Kafka\Protocol\ProtocolField;

class Topic extends AbstractStruct
{
    /**
     * The topic name.
     *
     * @var string
     */
    protected $name;

    /**
     * The number of partitions to create in the topic, or -1 if we are either specifying a manual partition assignment or using the default partitions.
     *
     * @var int
     */
    protected $numPartitions = -1;

    /**
     * The number of replicas to create for each partition in the topic, or -1 if we are either specifying a manual partition assignment or using the default replication factor.
     *
     * @var int
     */
    protected $replicationFactor = -1;

    /**
     * The manual partition assignment, or the empty array if we are using automatic assignment.
     *
     * @var TopicsAssignment[]
     */
    protected $assignments = [];

    /**
     * The custom topic configurations to set.
     *
     * @var TopicsConfig[]
     */
    protected $configs = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('name', 'CompactString', null, 5),
                new ProtocolField('name', 'String16', null, 0),
                new ProtocolField('numPartitions', 'Int32', null, 0),
                new ProtocolField('replicationFactor', 'Int16', null, 0),
                new ProtocolField('assignments', TopicsAssignment::class, 'CompactArray', 5),
                new ProtocolField('assignments', TopicsAssignment::class, 'ArrayInt32', 0),
                new ProtocolField('configs', TopicsConfig::class, 'CompactArray', 5),
                new ProtocolField('configs', TopicsConfig::class, 'ArrayInt32', 0),
            ];
            self::$taggedFieldses[self::class] = [];
        }
    }

    public function getFlexibleVersions(): ?int
    {
        return 5;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
     * @return TopicsAssignment[]
     */
    public function getAssignments(): array
    {
        return $this->assignments;
    }

    /**
     * @param TopicsAssignment[] $assignments
     */
    public function setAssignments(array $assignments): self
    {
        $this->assignments = $assignments;

        return $this;
    }

    /**
     * @return TopicsConfig[]
     */
    public function getConfigs(): array
    {
        return $this->configs;
    }

    /**
     * @param TopicsConfig[] $configs
     */
    public function setConfigs(array $configs): self
    {
        $this->configs = $configs;

        return $this;
    }
}
