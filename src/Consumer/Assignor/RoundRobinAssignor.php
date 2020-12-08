<?php

declare(strict_types=1);

namespace longlang\phpkafka\Consumer\Assignor;

use longlang\phpkafka\Group\Struct\ConsumerGroupMemberAssignment;
use longlang\phpkafka\Group\Struct\ConsumerGroupTopic;
use longlang\phpkafka\Protocol\JoinGroup\JoinGroupResponseMember;
use longlang\phpkafka\Protocol\Metadata\MetadataResponseTopic;
use longlang\phpkafka\Protocol\SyncGroup\SyncGroupRequestAssignment;

class RoundRobinAssignor extends AbstractPartitionAssignor
{
    /**
     * @param JoinGroupResponseMember[] $members
     *
     * @return SyncGroupRequestAssignment[]
     */
    public function assign(MetadataResponseTopic $metadata, array $members): array
    {
        $partitions = [];
        foreach ($metadata->getPartitions() as $item) {
            $partitions[spl_object_hash($item)] = $item->getPartitionIndex();
        }
        ksort($partitions);
        $assignments = [];
        $consumersForTopic = [];
        $memberPartitions = [];
        foreach ($members as $member) {
            $memberId = $member->getMemberId();
            $consumersForTopic[$memberId] = $member;
            $assignments[] = $assignment = new SyncGroupRequestAssignment();
            $assignment->setMemberId($memberId);
            $memberPartitions[$memberId] = [];
        }
        ksort($consumersForTopic);

        $memberCount = \count($members);

        $i = 0;
        foreach ($partitions as $partition) {
            $memberPartitions[$members[$i]->getMemberId()][] = $partition;
            if ($i >= $memberCount) {
                $i = 0;
            }
        }

        for ($i = 0; $i < $memberCount; ++$i) {
            $member = $members[$i];
            $consumerGroupMemberAssignment = new ConsumerGroupMemberAssignment();
            $consumerGroupTopic = new ConsumerGroupTopic();
            $consumerGroupTopic->setTopicName($metadata->getName());
            $consumerGroupTopic->setPartitions($memberPartitions[$member->getMemberId()]);
            $consumerGroupMemberAssignment->setTopics([$consumerGroupTopic]);
            $assignments[$i]->setAssignment($consumerGroupMemberAssignment->pack());
        }

        return $assignments;
    }
}
