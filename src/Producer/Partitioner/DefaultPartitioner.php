<?php

declare(strict_types=1);

namespace longlang\phpkafka\Producer\Partitioner;

use longlang\phpkafka\Protocol\Metadata\MetadataResponseTopic;

/**
 * The default partitioning strategy:.
 *
 * if partition !== null, then use partition
 * if partition === null && key !== null, then use crc32(key) % partitions to select partition
 * if partition === null && key === null, then use Round Robin to select partition
 */
class DefaultPartitioner implements PartitionerInterface
{
    /**
     * @var array
     */
    private $indexCache = [];

    /**
     * @param MetadataResponseTopic[] $topicsMeta
     */
    public function partition(string $topic, ?string $value, ?string $key, array $topicsMeta): int
    {
        if (null === $key) {
            return $this->roundRobin($topic, $topicsMeta);
        } else {
            return $this->hash($topic, $key, $topicsMeta);
        }
    }

    /**
     * @param MetadataResponseTopic[] $topicsMeta
     */
    private function roundRobin(string $topic, array $topicsMeta): int
    {
        $partitionCount = $this->getPartitionCount($topicsMeta, $topic);
        if (isset($this->indexCache[$topic])) {
            return (++$this->indexCache[$topic]) % $partitionCount;
        } else {
            return $this->indexCache[$topic] = mt_rand() % $partitionCount;
        }
    }

    private function hash(string $topic, string $key, array $topicsMeta): int
    {
        $partitionCount = $this->getPartitionCount($topicsMeta, $topic);

        return crc32($key) % $partitionCount;
    }

    private function getPartitionCount(array $topicsMeta, string $topic): int
    {
        foreach ($topicsMeta as $item) {
            if ($topic === $item->getName()) {
                return \count($item->getPartitions());
            }
        }
        throw new \RuntimeException(sprintf('Get topic %s partitions count failed', $topic));
    }
}
