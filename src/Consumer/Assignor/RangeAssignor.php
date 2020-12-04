<?php

declare(strict_types=1);

namespace longlang\phpkafka\Consumer\Assignor;

use longlang\phpkafka\Group\Struct\ConsumerGroupMemberAssignment;
use longlang\phpkafka\Group\Struct\ConsumerGroupTopic;
use longlang\phpkafka\Protocol\JoinGroup\JoinGroupResponseMember;
use longlang\phpkafka\Protocol\Metadata\MetadataResponseTopic;
use longlang\phpkafka\Protocol\SyncGroup\SyncGroupRequestAssignment;

class RangeAssignor extends AbstractPartitionAssignor
{
    /**
     * @param JoinGroupResponseMember[] $members
     *
     * @return SyncGroupRequestAssignment[]
     */
    public function assign(MetadataResponseTopic $metadata, array $members): array
    {
        $assignments = [];
        $partitions = $metadata->getPartitions();
        $consumersForTopic = [];
        foreach ($members as $member) {
            $consumersForTopic[$member->getMemberId()] = $member;
            $assignments[] = $assignment = new SyncGroupRequestAssignment();
            $assignment->setMemberId($member->getMemberId());
        }
        ksort($consumersForTopic);

        $partitionCount = \count($partitions);
        $memberCount = \count($members);
        $numPartitionsPerConsumer = (int) ($partitionCount / $memberCount);
        $consumersWithExtraPartition = $partitionCount % $memberCount;

        for ($i = 0; $i < $memberCount; ++$i) {
            $start = $numPartitionsPerConsumer * $i + min($i, $consumersWithExtraPartition);
            $length = $numPartitionsPerConsumer + ($i + 1 > $consumersWithExtraPartition ? 0 : 1);

            $consumerGroupMemberAssignment = new ConsumerGroupMemberAssignment();
            $consumerGroupTopic = new ConsumerGroupTopic();
            $consumerGroupTopic->setTopicName($metadata->getName());
            $consumerGroupTopic->setPartitions(range($start, $start + $length - 1));
            $consumerGroupMemberAssignment->setTopics([$consumerGroupTopic]);
            $assignments[$i]->setAssignment($consumerGroupMemberAssignment->pack());
        }

        return $assignments;
    }
}
