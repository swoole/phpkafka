<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\CreateTopics;

use Longyan\Kafka\Protocol\AbstractStruct;
use Longyan\Kafka\Protocol\ProtocolField;

class CreatableReplicaAssignment extends AbstractStruct
{
    /**
     * The partition index.
     *
     * @var int
     */
    protected $partitionIndex;

    /**
     * The brokers to place the partition on.
     *
     * @var int32[]
     */
    protected $brokerId = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('partitionIndex', 'int32', false, [0, 1, 2, 3, 4, 5], [5], [], [], null),
                new ProtocolField('brokerId', 'int32', true, [0, 1, 2, 3, 4, 5], [5], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [5];
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
    public function getBrokerId(): array
    {
        return $this->brokerId;
    }

    /**
     * @param int32[] $brokerId
     */
    public function setBrokerId(array $brokerId): self
    {
        $this->brokerId = $brokerId;

        return $this;
    }
}
