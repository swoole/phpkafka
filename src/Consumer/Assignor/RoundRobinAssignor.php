<?php

declare(strict_types=1);

namespace longlang\phpkafka\Consumer\Assignor;

use longlang\phpkafka\Consumer\Assignor\Struct\TopicAndPartition;
use longlang\phpkafka\Consumer\Struct\ConsumerGroupMemberMetadata;
use longlang\phpkafka\Group\Struct\ConsumerGroupMemberAssignment;
use longlang\phpkafka\Group\Struct\ConsumerGroupTopic;
use longlang\phpkafka\Protocol\JoinGroup\JoinGroupResponseMember;
use longlang\phpkafka\Protocol\Metadata\MetadataResponseTopic;
use longlang\phpkafka\Protocol\SyncGroup\SyncGroupRequestAssignment;

class RoundRobinAssignor extends AbstractPartitionAssignor
{
    /**
     * @param MetadataResponseTopic[]   $topicMetadatas
     * @param JoinGroupResponseMember[] $groupMembers
     *
     * @return SyncGroupRequestAssignment[]
     */
    public function assign(array $topicMetadatas, array $groupMembers): array
    {
        $memberIds = [];
        $topicMemberIds = [];
        /** @var TopicAndPartition[] $topicAndPartitions */
        $topicAndPartitions = [];
        foreach ($groupMembers as $groupMember) {
            $memberId = $groupMember->getMemberId();
            $memberIds[] = $memberId;
            $consumerGroupMemberMetadata = new ConsumerGroupMemberMetadata();
            $consumerGroupMemberMetadata->unpack($groupMember->getMetadata());
            foreach ($consumerGroupMemberMetadata->getTopics() as $topic) {
                $topicMemberIds[$topic][] = $memberId;
            }
        }
        $topicIndexs = [];
        foreach ($topicMetadatas as $topicMetadata) {
            $topic = $topicMetadata->getName();
            if (!isset($topicMemberIds[$topic])) {
                continue;
            }
            if (!isset($topicIndexs[$topic])) {
                $topicIndexs[$topic] = 0;
                foreach ($this->getTopicPartitions($topic, $topicMetadatas) as $partition) {
                    $topicAndPartition = new TopicAndPartition($topic, $partition);
                    $topicAndPartitions[spl_object_hash($topicAndPartition)] = $topicAndPartition;
                }
            }
        }
        ksort($topicAndPartitions);

        $assignments = [];
        $memberPartitions = [];
        foreach ($memberIds as $memberId) {
            $assignments[] = $assignment = new SyncGroupRequestAssignment();
            $assignment->setMemberId($memberId);
            $memberPartitions[$memberId] = [];
        }

        foreach ($topicAndPartitions as $topicAndPartition) {
            $topic = $topicAndPartition->getTopic();
            $memberCount = \count($topicMemberIds[$topic]);
            $i = &$topicIndexs[$topic];
            $memberPartitions[$topicMemberIds[$topic][$i]][] = $topicAndPartition;
            ++$i;
            if ($i >= $memberCount) {
                $i = 0;
            }
            unset($i);
        }

        $i = 0;
        foreach ($memberPartitions as $memberId => $topicAndPartitions) {
            $consumerGroupMemberAssignment = new ConsumerGroupMemberAssignment();
            $consumerGroupTopics = [];
            /** @var TopicAndPartition[] $topicAndPartitions */
            foreach ($topicAndPartitions as $topicAndPartition) {
                $topic = $topicAndPartition->getTopic();
                if (isset($consumerGroupTopics[$topic])) {
                    $consumerGroupTopic = $consumerGroupTopics[$topic];
                } else {
                    $consumerGroupTopic = $consumerGroupTopics[$topic] = new ConsumerGroupTopic();
                    $consumerGroupTopic->setTopicName($topic);
                }
                $partitions = $consumerGroupTopic->getPartitions();
                $partitions[] = $topicAndPartition->getPartition();
                $consumerGroupTopic->setPartitions($partitions);
            }
            $consumerGroupMemberAssignment->setTopics($consumerGroupTopics);
            $assignments[$i]->setAssignment($consumerGroupMemberAssignment->pack());
            ++$i;
        }

        return $assignments;
    }
}
