<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\AlterReplicaLogDirs;

use Longyan\Kafka\Protocol\AbstractStruct;
use Longyan\Kafka\Protocol\ProtocolField;

class AlterReplicaLogDirTopic extends AbstractStruct
{
    /**
     * The topic name.
     *
     * @var string
     */
    protected $name = '';

    /**
     * The partition indexes.
     *
     * @var int32[]
     */
    protected $partitions = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('name', 'string', false, [0, 1], [], [], [], null),
                new ProtocolField('partitions', 'int32', true, [0, 1], [], [], [], null),
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
     * @return int32[]
     */
    public function getPartitions(): array
    {
        return $this->partitions;
    }

    /**
     * @param int32[] $partitions
     */
    public function setPartitions(array $partitions): self
    {
        $this->partitions = $partitions;

        return $this;
    }
}
