<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\ElectLeaders;

use Longyan\Kafka\Protocol\AbstractStruct;
use Longyan\Kafka\Protocol\ProtocolField;

class ReplicaElectionResult extends AbstractStruct
{
    /**
     * The topic name.
     *
     * @var string
     */
    protected $topicName;

    /**
     * The results for each partition.
     *
     * @var PartitionResult[]
     */
    protected $partitionResult = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('topicName', 'string', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('partitionResult', PartitionResult::class, true, [0, 1, 2], [2], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [2];
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

    /**
     * @return PartitionResult[]
     */
    public function getPartitionResult(): array
    {
        return $this->partitionResult;
    }

    /**
     * @param PartitionResult[] $partitionResult
     */
    public function setPartitionResult(array $partitionResult): self
    {
        $this->partitionResult = $partitionResult;

        return $this;
    }
}
