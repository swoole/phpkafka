<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\OffsetDelete;

use Longyan\Kafka\Protocol\AbstractStruct;
use Longyan\Kafka\Protocol\ProtocolField;

class OffsetDeleteResponseTopic extends AbstractStruct
{
    /**
     * The topic name.
     *
     * @var string
     */
    protected $name;

    /**
     * The responses for each partition in the topic.
     *
     * @var OffsetDeleteResponsePartition[]
     */
    protected $partitions = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('name', 'string', false, [0], [], [], [], null),
                new ProtocolField('partitions', OffsetDeleteResponsePartition::class, true, [0], [], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [];
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
     * @return OffsetDeleteResponsePartition[]
     */
    public function getPartitions(): array
    {
        return $this->partitions;
    }

    /**
     * @param OffsetDeleteResponsePartition[] $partitions
     */
    public function setPartitions(array $partitions): self
    {
        $this->partitions = $partitions;

        return $this;
    }
}
