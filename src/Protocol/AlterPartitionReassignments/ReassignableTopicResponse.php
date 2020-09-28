<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\AlterPartitionReassignments;

use Longyan\Kafka\Protocol\AbstractStruct;
use Longyan\Kafka\Protocol\ProtocolField;

class ReassignableTopicResponse extends AbstractStruct
{
    /**
     * The topic name.
     *
     * @var string
     */
    protected $topicName;

    /**
     * The responses to partitions to reassign.
     *
     * @var ReassignablePartitionResponse[]
     */
    protected $partitions = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('topicName', 'string', false, [0], [0], [], [], null),
                new ProtocolField('partitions', ReassignablePartitionResponse::class, true, [0], [0], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [0];
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
     * @return ReassignablePartitionResponse[]
     */
    public function getPartitions(): array
    {
        return $this->partitions;
    }

    /**
     * @param ReassignablePartitionResponse[] $partitions
     */
    public function setPartitions(array $partitions): self
    {
        $this->partitions = $partitions;

        return $this;
    }
}
