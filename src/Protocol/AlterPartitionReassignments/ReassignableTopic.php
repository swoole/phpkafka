<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\AlterPartitionReassignments;

use Longyan\Kafka\Protocol\AbstractStruct;
use Longyan\Kafka\Protocol\ProtocolField;

class ReassignableTopic extends AbstractStruct
{
    /**
     * The topic name.
     *
     * @var string
     */
    protected $name = '';

    /**
     * The partitions to reassign.
     *
     * @var ReassignablePartition[]
     */
    protected $partitions = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('name', 'string', false, [0], [0], [], [], null),
                new ProtocolField('partitions', ReassignablePartition::class, true, [0], [0], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [0];
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

    /**
     * @return ReassignablePartition[]
     */
    public function getPartitions(): array
    {
        return $this->partitions;
    }

    /**
     * @param ReassignablePartition[] $partitions
     */
    public function setPartitions(array $partitions): self
    {
        $this->partitions = $partitions;

        return $this;
    }
}
