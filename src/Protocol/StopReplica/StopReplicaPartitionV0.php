<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\StopReplica;

use Longyan\Kafka\Protocol\AbstractStruct;
use Longyan\Kafka\Protocol\ProtocolField;

class StopReplicaPartitionV0 extends AbstractStruct
{
    /**
     * The topic name.
     *
     * @var string
     */
    protected $topicName = '';

    /**
     * The partition index.
     *
     * @var int
     */
    protected $partitionIndex = 0;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('topicName', 'string', false, [0], [2, 3], [], [], null),
                new ProtocolField('partitionIndex', 'int32', false, [0], [2, 3], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [2, 3];
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

    public function getPartitionIndex(): int
    {
        return $this->partitionIndex;
    }

    public function setPartitionIndex(int $partitionIndex): self
    {
        $this->partitionIndex = $partitionIndex;

        return $this;
    }
}
