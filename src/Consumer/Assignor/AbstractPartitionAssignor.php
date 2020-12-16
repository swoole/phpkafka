<?php

declare(strict_types=1);

namespace longlang\phpkafka\Consumer\Assignor;

use longlang\phpkafka\Protocol\Metadata\MetadataResponseTopic;

abstract class AbstractPartitionAssignor implements PartitionAssignorInterface
{
    /**
     * @param string[] $topics
     */
    public function subscriptionUserData(array $topics): string
    {
        return '';
    }

    /**
     * @param MetadataResponseTopic[] $topicMetadatas
     */
    protected function getTopicPartitions(string $topic, array $topicMetadatas): array
    {
        foreach ($topicMetadatas as $metadata) {
            if ($metadata->getName() === $topic) {
                $partitions = [];
                foreach ($metadata->getPartitions() as $partition) {
                    $partitions[] = $partition->getPartitionIndex();
                }

                return $partitions;
            }
        }

        return [];
    }
}
