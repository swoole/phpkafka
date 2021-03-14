<?php

declare(strict_types=1);

namespace longlang\phpkafka\Consumer\Assignor;

use longlang\phpkafka\Protocol\JoinGroup\JoinGroupResponseMember;
use longlang\phpkafka\Protocol\Metadata\MetadataResponseTopic;
use longlang\phpkafka\Protocol\SyncGroup\SyncGroupRequestAssignment;

interface PartitionAssignorInterface
{
    /**
     * @param string[] $topics
     */
    public function subscriptionUserData(array $topics): string;

    /**
     * @param MetadataResponseTopic[]   $topicMetadatas
     * @param JoinGroupResponseMember[] $groupMembers
     *
     * @return SyncGroupRequestAssignment[]
     */
    public function assign(array $topicMetadatas, array $groupMembers): array;
}
