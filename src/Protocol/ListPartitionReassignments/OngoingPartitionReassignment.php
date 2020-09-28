<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\ListPartitionReassignments;

use Longyan\Kafka\Protocol\AbstractStruct;
use Longyan\Kafka\Protocol\ProtocolField;

class OngoingPartitionReassignment extends AbstractStruct
{
    /**
     * The index of the partition.
     *
     * @var int
     */
    protected $partitionIndex;

    /**
     * The current replica set.
     *
     * @var int32[]
     */
    protected $replicas = [];

    /**
     * The set of replicas we are currently adding.
     *
     * @var int32[]
     */
    protected $addingReplicas = [];

    /**
     * The set of replicas we are currently removing.
     *
     * @var int32[]
     */
    protected $removingReplicas = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('partitionIndex', 'int32', false, [0], [0], [], [], null),
                new ProtocolField('replicas', 'int32', true, [0], [0], [], [], null),
                new ProtocolField('addingReplicas', 'int32', true, [0], [0], [], [], null),
                new ProtocolField('removingReplicas', 'int32', true, [0], [0], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [0];
    }

    public function getPartitionIndex(): int
    {
        return $this->partitionIndex;
    }

    public function setPartitionIndex(int $partitionIndex): self
    {
        $this->partitionIndex = $partitionIndex;

        return $this;
    }

    /**
     * @return int32[]
     */
    public function getReplicas(): array
    {
        return $this->replicas;
    }

    /**
     * @param int32[] $replicas
     */
    public function setReplicas(array $replicas): self
    {
        $this->replicas = $replicas;

        return $this;
    }

    /**
     * @return int32[]
     */
    public function getAddingReplicas(): array
    {
        return $this->addingReplicas;
    }

    /**
     * @param int32[] $addingReplicas
     */
    public function setAddingReplicas(array $addingReplicas): self
    {
        $this->addingReplicas = $addingReplicas;

        return $this;
    }

    /**
     * @return int32[]
     */
    public function getRemovingReplicas(): array
    {
        return $this->removingReplicas;
    }

    /**
     * @param int32[] $removingReplicas
     */
    public function setRemovingReplicas(array $removingReplicas): self
    {
        $this->removingReplicas = $removingReplicas;

        return $this;
    }
}
