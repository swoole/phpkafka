<?php

declare(strict_types=1);

namespace longlang\phpkafka\Producer\Partitioner;

use longlang\phpkafka\Protocol\Metadata\MetadataResponseTopic;

interface PartitionerInterface
{
    /**
     * @param MetadataResponseTopic[] $topicsMeta
     */
    public function partition(string $topic, ?string $value, ?string $key, array $topicsMeta): int;
}
