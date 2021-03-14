<?php

declare(strict_types=1);

namespace longlang\phpkafka\Consumer\Assignor;

use chdemko\SortedCollection\TreeSet;
use longlang\phpkafka\Consumer\Assignor\Struct\TopicAndPartition;
use longlang\phpkafka\Consumer\Struct\ConsumerGenerationPair;
use longlang\phpkafka\Consumer\Struct\ConsumerGroupMemberMetadata;
use longlang\phpkafka\Consumer\Struct\StickyAssignorUserData;
use longlang\phpkafka\Consumer\Struct\TopicPartition;
use longlang\phpkafka\Group\Struct\ConsumerGroupMemberAssignment;
use longlang\phpkafka\Group\Struct\ConsumerGroupTopic;
use longlang\phpkafka\Protocol\JoinGroup\JoinGroupResponseMember;
use longlang\phpkafka\Protocol\Metadata\MetadataResponseTopic;
use longlang\phpkafka\Protocol\SyncGroup\SyncGroupRequestAssignment;
use longlang\phpkafka\Util\ObjectKeyArray;

class StickyAssignor extends AbstractPartitionAssignor
{
    const DEFAULT_GENERATION = -1;

    /**
     * @var PartitionMovements
     */
    private $partitionMovements;

    /**
     * @param MetadataResponseTopic[]   $topicMetadatas
     * @param JoinGroupResponseMember[] $groupMembers
     *
     * @return SyncGroupRequestAssignment[]
     */
    public function assign(array $topicMetadatas, array $groupMembers): array
    {
        $partitions = $memberTopics = $consumerToOwnedPartitions = [];
        if ($this->allSubscriptionsEqual($topicMetadatas, $groupMembers, $partitions, $memberTopics, $consumerToOwnedPartitions)) {
            $assignment = $this->constrainedAssign($partitions, $memberTopics, $consumerToOwnedPartitions);
        } else {
            $assignment = $this->generalAssign($topicMetadatas, $groupMembers, $partitions, $memberTopics);
        }
        /** @var ConsumerGroupMemberAssignment[] $consumerGroupMemberAssignments */
        $consumerGroupMemberAssignments = [];
        /** @var SyncGroupRequestAssignment[] $result */
        $result = [];
        foreach ($assignment as $memberId => $topicPartitionsList) {
            $consumerGroupMemberAssignment = $consumerGroupMemberAssignments[$memberId] = new ConsumerGroupMemberAssignment();

            $topicPartitions = [];
            foreach ($topicPartitionsList as $topicPartitionsItem) {
                $topicPartitions[$topicPartitionsItem->getTopic()][] = $topicPartitionsItem->getPartition();
            }

            $topics = [];
            foreach ($topicPartitions as $topic => $partitions) {
                $topics[$topic] = (new ConsumerGroupTopic())->setTopicName($topic)->setPartitions($partitions);
            }

            $consumerGroupMemberAssignment->setTopics($topics);

            $result[$memberId] = (new SyncGroupRequestAssignment())->setMemberId($memberId);
        }
        foreach ($result as $memberId => $item) {
            $item->setAssignment($consumerGroupMemberAssignments[$memberId]->pack());
        }

        return $result;
    }

    /**
     * @param MetadataResponseTopic[] $topicMetadatas
     */
    private function getAllTopics(array $topicMetadatas): array
    {
        $topics = [];
        foreach ($topicMetadatas as $metadata) {
            $topics[] = $metadata->getName();
        }

        return $topics;
    }

    /**
     * Returns true iff all consumers have an identical subscription. Also fills out the passed in consumerToOwnedPartitions with each consumer's previously owned and still-subscribed partitions.
     *
     * @param MetadataResponseTopic[]   $topicMetadatas
     * @param JoinGroupResponseMember[] $groupMembers
     */
    private function allSubscriptionsEqual(array $topicMetadatas, array $groupMembers, array &$partitions, array &$memberTopics, array &$consumerToOwnedPartitions): bool
    {
        $allTopics = $this->getAllTopics($topicMetadatas);
        $partitions = [];
        $memberTopics = [];
        $consumerToOwnedPartitions = [];
        $membersWithOldGeneration = $membersOfCurrentHighestGeneration = [];
        $maxGeneration = self::DEFAULT_GENERATION;
        $compareTopics = null;
        foreach ($groupMembers as $groupMember) {
            $memberId = $groupMember->getMemberId();
            $consumerGroupMemberMetadata = new ConsumerGroupMemberMetadata();
            $consumerGroupMemberMetadata->unpack($groupMember->getMetadata());
            $memberData = new StickyAssignorUserData();
            $data = $consumerGroupMemberMetadata->getUserData();
            if ('' !== $data) {
                $memberData->unpack($data);
            }
            $memberTopics[$memberId] = $topics = $consumerGroupMemberMetadata->getTopics();
            foreach ($topics as $topic) {
                if (!isset($partitions[$topic])) {
                    $partitions[$topic] = $this->getTopicPartitions($topic, $topicMetadatas);
                    sort($partitions[$topic]);
                }
            }

            sort($topics);
            if (null === $compareTopics) {
                $compareTopics = $topics;
            } elseif ($compareTopics !== $topics) {
                return false;
            }

            $ownedPartitions = [];
            $generation = $memberData->getGeneration();

            // Only consider this consumer's owned partitions as valid if it is a member of the current highest generation, or it's generation is not present but we have not seen any known generation so far
            // @phpstan-ignore-next-line
            if ((null !== $generation && $generation >= $maxGeneration) || (null === $generation && self::DEFAULT_GENERATION === $generation)) {
                // If the current member's generation is higher, all the previously owned partitions are invalid
                if (null !== $generation && $generation > $maxGeneration) {
                    $membersWithOldGeneration = array_merge($membersWithOldGeneration, $membersOfCurrentHighestGeneration);
                    $membersOfCurrentHighestGeneration = [];
                    $maxGeneration = $generation;
                }
                $membersOfCurrentHighestGeneration[] = $memberId;
                foreach ($memberData->getPartitions() as $partition) {
                    if (\in_array($partition->getTopic(), $allTopics)) {
                        $ownedPartitions[] = $partition;
                    }
                }
            }

            $consumerToOwnedPartitions[$memberId] = $ownedPartitions;
        }

        foreach ($membersWithOldGeneration as $consumer) {
            $consumerToOwnedPartitions[$consumer] = [];
        }

        return true;
    }

    /**
     * @return TopicAndPartition[][]
     */
    private function constrainedAssign(array $partitions, array $memberTopics, array $consumerToOwnedPartitions): array
    {
        $unassignedPartitions = $this->getAllPartitions($partitions);
        $unassignedPartitionsCount = \count($unassignedPartitions);
        $allRevokedPartitions = [];
        $unfilledMembers = [];
        $maxCapacityMembers = [];
        $minCapacityMembers = [];

        $numberOfConsumers = \count($memberTopics);
        $minQuota = (int) ($unassignedPartitionsCount / $numberOfConsumers);
        $maxQuota = (int) ceil($unassignedPartitionsCount / $numberOfConsumers);

        $assignment = [];

        // Reassign as many previously owned partitions as possible
        foreach ($consumerToOwnedPartitions as $consumer => $ownedPartitions) {
            $i = 0;
            /** @var TopicAndPartition[] $ownedPartitions */
            foreach ($ownedPartitions as $tp) {
                if ($i < $maxQuota) {
                    $assignment[$consumer][] = $tp;
                    unset($unassignedPartitions[$tp->getTopic() . '-' . $tp->getPartition()]);
                } else {
                    $allRevokedPartitions[] = $tp;
                }
                ++$i;
            }

            if (\count($ownedPartitions) < $minQuota) {
                $unfilledMembers[] = $consumer;
            } else {
                // It's possible for a consumer to be at both min and max capacity if minQuota == maxQuota
                $consumerAssignmentCount = \count($assignment[$consumer]);
                if ($consumerAssignmentCount === $minQuota) {
                    $minCapacityMembers[] = $consumer;
                }
                if ($consumerAssignmentCount === $maxQuota) {
                    $maxCapacityMembers[] = $consumer;
                }
            }
        }

        sort($unfilledMembers);

        // Fill remaining members up to minQuota
        reset($unassignedPartitions);
        while (!empty($unfilledMembers) && !empty($unassignedPartitions)) {
            foreach ($unfilledMembers as $consumerKey => $consumer) {
                /** @var TopicAndPartition $tp */
                $tp = current($unassignedPartitions);
                // @phpstan-ignore-next-line
                if (false === $tp) {
                    break;
                }
                $assignment[$consumer][] = $tp;
                unset($unassignedPartitions[key($unassignedPartitions)]);
                if (\count($assignment[$consumer]) === $minQuota) {
                    $minCapacityMembers[] = $consumer;
                    unset($unfilledMembers[$consumerKey]);
                }
            }
        }

        // If we ran out of unassigned partitions before filling all consumers, we need to start stealing partitions from the over-full consumers at max capacity
        foreach ($unfilledMembers as $consumer) {
            $remainingCapacity = $minQuota - \count($assignment[$consumer]);
            while ($remainingCapacity > 0) {
                $overloadedConsumer = array_shift($maxCapacityMembers);
                if (null === $overloadedConsumer) {
                    throw new \RuntimeException('Some consumers are under capacity but all partitions have been assigned');
                }
                $swappedPartition = array_shift($assignment[$overloadedConsumer]);
                $assignment[$consumer][] = $swappedPartition;
                --$remainingCapacity;
            }
            $minCapacityMembers[] = $consumer;
        }

        // Otherwise we may have run out of unfilled consumers before assigning all partitions, in which case we should just distribute one partition each to all consumers at min capacity
        foreach ($unassignedPartitions as $unassignedPartition) {
            $underCapacityConsumer = array_shift($minCapacityMembers);
            if (null === $underCapacityConsumer) {
                throw new \RuntimeException('Some partitions are unassigned but all consumers are at maximum capacity');
            }
            // We can skip the bookkeeping of unassignedPartitions and maxCapacityMembers here since we are at the end
            $assignment[$underCapacityConsumer][] = $unassignedPartition;
        }

        return $assignment;
    }

    /**
     * @param MetadataResponseTopic[]   $topicMetadatas
     * @param JoinGroupResponseMember[] $groupMembers
     *
     * @return TopicAndPartition[][]
     */
    private function generalAssign(array $topicMetadatas, array $groupMembers, array $partitions, array $memberTopics): array
    {
        $unassignedPartitions = $this->getAllPartitions($partitions);
        /** @var TopicPartition[][] $currentAssignment */
        $currentAssignment = [];
        $prevAssignment = new ObjectKeyArray();
        $this->partitionMovements = new PartitionMovements();
        $this->prepopulateCurrentAssignments($groupMembers, $currentAssignment, $prevAssignment);

        // a mapping of all topic partitions to all consumers that can be assigned to them
        $partition2AllPotentialConsumers = new ObjectKeyArray();

        // a mapping of all consumers to all potential topic partitions that can be assigned to them
        $consumer2AllPotentialPartitions = [];

        // initialize partition2AllPotentialConsumers and consumer2AllPotentialPartitions in the following two for loops
        foreach ($topicMetadatas as $entry) {
            $partitionsSize = \count($entry->getPartitions());
            for ($i = 0; $i < $partitionsSize; ++$i) {
                $partition2AllPotentialConsumers[new TopicPartition($entry->getName(), $i)] = [];
            }
        }

        foreach ($groupMembers as $groupMember) {
            $consumerId = $groupMember->getMemberId();
            $consumer2AllPotentialPartitions[$consumerId] = [];
            $consumerGroupMemberMetadata = new ConsumerGroupMemberMetadata();
            $consumerGroupMemberMetadata->unpack($groupMember->getMetadata());
            $memberData = new StickyAssignorUserData();
            $data = $consumerGroupMemberMetadata->getUserData();
            if ('' !== $data) {
                $memberData->unpack($data);
            }
            foreach ($consumerGroupMemberMetadata->getTopics() as $topic) {
                if (isset($partitions[$topic])) {
                    $size = \count($partitions[$topic]);
                    for ($i = 0; $i < $size; ++$i) {
                        $topicPartition = new TopicPartition($topic, $i);
                        $consumer2AllPotentialPartitions[$consumerId][] = $topicPartition;
                        $partition2AllPotentialConsumers[$topicPartition][] = $consumerId;
                    }
                }
            }
            // add this consumer to currentAssignment (with an empty topic partition assignment) if it does not already exist
            if (!isset($currentAssignment[$consumerId])) {
                $currentAssignment[$consumerId] = [];
            }
        }

        // a mapping of partition to current consumer
        $currentPartitionConsumer = new ObjectKeyArray();
        foreach ($currentAssignment as $key => $entry) {
            foreach ($entry as $topicPartition) {
                $currentPartitionConsumer[$topicPartition] = $key;
            }
        }

        $sortedPartitions = $this->sortPartitions($partition2AllPotentialConsumers);

        // all partitions that need to be assigned (initially set to all partitions but adjusted in the following loop)
        $unassignedPartitions = $sortedPartitions;
        $revocationRequired = false;
        foreach ($currentAssignment as $key => &$entry) {
            if (isset($partitions[$key])) {
                // otherwise (the consumer still exists)
                foreach ($entry as $key2 => $partition) {
                    if (!isset($partition2AllPotentialConsumers[$partition])) {
                        // if this topic partition of this consumer no longer exists remove it from currentAssignment of the consumer
                        unset($entry[$key2], $currentPartitionConsumer[$partition]);
                    } elseif (\in_array($partition->getTopic(), $memberTopics[$key])) {
                        // if this partition cannot remain assigned to its current consumer because the consumer is no longer subscribed to its topic remove it from currentAssignment of the consumer
                        unset($entry[$key2]);
                        $revocationRequired = true;
                    } else {
                        // otherwise, remove the topic partition from those that need to be assigned only if its current consumer is still subscribed to its topic (because it is already assigned and we would want to preserve that assignment as much as possible)
                        unset($unassignedPartitions[$partition]);
                    }
                }
            } else {
                // if a consumer that existed before (and had some partition assignments) is now removed, remove it from currentAssignment
                foreach ($entry as $topicPartition) {
                    unset($currentPartitionConsumer[$topicPartition]);
                }
            }
        }
        unset($entry);
        // at this point we have preserved all valid topic partition to consumer assignments and removed all invalid topic partitions and invalid consumers. Now we need to assign unassignedPartitions to consumers so that the topic partition assignments are as balanced as possible.

        // an ascending sorted set of consumers based on how many topic partitions are already assigned to them
        $sortedCurrentSubscriptions = TreeSet::create(function (string $key1, string $key2) use (&$currentAssignment) {
            $ret = \count($currentAssignment[$key1]) - \count($currentAssignment[$key2]);
            if (0 === $ret) {
                $ret = substr_compare($key1, $key2, 0);
            }

            return $ret;
            // @phpstan-ignore-next-line
        })->put(array_keys($currentAssignment));

        $this->balance($currentAssignment, $prevAssignment, $sortedPartitions, $unassignedPartitions, $sortedCurrentSubscriptions,
        $consumer2AllPotentialPartitions, $partition2AllPotentialConsumers, $currentPartitionConsumer, $revocationRequired);

        return $currentAssignment;
    }

    private function balance(array &$currentAssignment, ObjectKeyArray $prevAssignment, array &$sortedPartitions, array &$unassignedPartitions, TreeSet $sortedCurrentSubscriptions,
    array &$consumer2AllPotentialPartitions, ObjectKeyArray $partition2AllPotentialConsumers, ObjectKeyArray $currentPartitionConsumer, bool $revocationRequired): void
    {
        $initializing = empty($currentAssignment[$sortedCurrentSubscriptions->last()]);
        $reassignmentPerformed = false;

        // assign all unassigned partitions
        foreach ($unassignedPartitions as $partition) {
            /** @var TopicPartition $partition */
            if (empty($partition2AllPotentialConsumers[$partition])) {
                continue;
            }
            $this->assignPartition($partition, $sortedCurrentSubscriptions, $currentAssignment, $consumer2AllPotentialPartitions, $currentPartitionConsumer);
        }

        // narrow down the reassignment scope to only those partitions that can actually be reassigned
        $fixedPartitions = [];
        foreach ($partition2AllPotentialConsumers as $partition => $_) {
            /** @var TopicPartition $partition */
            if (!$this->canParticipateInReassignment2($partition, $partition2AllPotentialConsumers)) {
                $fixedPartitions[] = $partition;
            }
        }
        foreach ($fixedPartitions as $partition1) {
            foreach ($sortedPartitions as $key => $partition2) {
                if ($partition1 === $partition2) {
                    unset($sortedPartitions[$key]);
                }
            }
            foreach ($unassignedPartitions as $key => $partition2) {
                if ($partition1 === $partition2) {
                    unset($unassignedPartitions[$key]);
                }
            }
        }

        // narrow down the reassignment scope to only those consumers that are subject to reassignment
        $fixedAssignments = [];
        foreach ($consumer2AllPotentialPartitions as $consumer => $_) {
            if (!$this->canParticipateInReassignment4($consumer, $currentAssignment, $consumer2AllPotentialPartitions, $partition2AllPotentialConsumers)) {
                unset($sortedCurrentSubscriptions[$consumer]);
                $fixedAssignments[$consumer] = $currentAssignment[$consumer];
                unset($currentAssignment[$consumer]);
            }
        }

        // create a deep copy of the current assignment so we can revert to it if we do not get a more balanced assignment later
        $preBalanceAssignment = $currentAssignment;
        $preBalancePartitionConsumers = $currentPartitionConsumer;

        // if we don't already need to revoke something due to subscription changes, first try to balance by only moving newly added partitions
        if (!$revocationRequired) {
            $this->performReassignments($unassignedPartitions, $currentAssignment, $prevAssignment, $sortedCurrentSubscriptions, $consumer2AllPotentialPartitions, $partition2AllPotentialConsumers, $currentPartitionConsumer);
        }

        $reassignmentPerformed = $this->performReassignments($sortedPartitions, $currentAssignment, $prevAssignment, $sortedCurrentSubscriptions, $consumer2AllPotentialPartitions, $partition2AllPotentialConsumers, $currentPartitionConsumer);

        // if we are not preserving existing assignments and we have made changes to the current assignment make sure we are getting a more balanced assignment; otherwise, revert to previous assignment
        if (!$initializing && $reassignmentPerformed && $this->getBalanceScore($currentAssignment) >= $this->getBalanceScore($preBalanceAssignment)) {
            $currentAssignment = $preBalanceAssignment;
            $currentPartitionConsumer = [];
            foreach ($preBalancePartitionConsumers as $key => $value) {
                $currentPartitionConsumer[$key] = $value;
            }
        }

        // add the fixed assignments (those that could not change) back
        foreach ($fixedAssignments as $consumer => $entry) {
            $currentAssignment[$consumer] = $entry;
        }
        // @phpstan-ignore-next-line
        $sortedCurrentSubscriptions->put(array_keys($fixedAssignments));

        $fixedAssignments = [];
    }

    /**
     * @param JoinGroupResponseMember[] $groupMembers
     * @param TopicPartition[][]        $currentAssignment
     */
    private function prepopulateCurrentAssignments(array $groupMembers, array &$currentAssignment, ObjectKeyArray $prevAssignment): void
    {
        // we need to process subscriptions' user data with each consumer's reported generation in mind higher generations overwrite lower generations in case of a conflict note that a conflict could exists only if user data is for different generations

        // for each partition we create a sorted map of its consumers by generation
        $sortedPartitionConsumersByGeneration = new ObjectKeyArray();
        foreach ($groupMembers as $groupMember) {
            $consumer = $groupMember->getMemberId();
            $consumerGroupMemberMetadata = new ConsumerGroupMemberMetadata();
            $consumerGroupMemberMetadata->unpack($groupMember->getMetadata());
            $memberData = new StickyAssignorUserData();
            $data = $consumerGroupMemberMetadata->getUserData();
            if ('' !== $data) {
                $memberData->unpack($data);
            }
            $generation = $memberData->getGeneration();
            foreach ($memberData->getPartitions() as $partition) {
                if (isset($sortedPartitionConsumersByGeneration[$partition])) {
                    $consumers = $sortedPartitionConsumersByGeneration[$partition];
                    if (null !== $generation && isset($consumers[$generation])) {
                        // same partition is assigned to two consumers during the same rebalance. log a warning and skip this record
                        trigger_error(sprintf('Partition \'%s\' is assigned to multiple consumers following sticky assignment generation %s.', $partition, $generation), \E_USER_WARNING);
                    } else {
                        $consumers[$generation] = $consumer;
                    }
                } else {
                    $sortedConsumers = [];
                    $sortedConsumers[$generation] = $consumer;
                    $sortedPartitionConsumersByGeneration[$partition] = $sortedConsumers;
                }
            }
        }

        // prevAssignment holds the prior ConsumerGenerationPair (before current) of each partition current and previous consumers are the last two consumers of each partition in the above sorted map
        foreach ($sortedPartitionConsumersByGeneration as $partition => $consumers) {
            // let's process the current (most recent) consumer first
            $consumer = reset($consumers);
            $currentAssignment[$consumer][] = $partition;

            // now update previous assignment if any
            $generation = next($consumers);
            if (false !== $generation) {
                $prevAssignment[$partition] = new ConsumerGenerationPair($consumers[$generation], $generation);
            }
        }
    }

    /**
     * @return TopicAndPartition[]
     */
    private function getAllPartitions(array $partitions): array
    {
        $result = [];
        ksort($partitions);
        foreach ($partitions as $topic => $partitionIndexs) {
            sort($partitionIndexs);
            foreach ($partitionIndexs as $index) {
                $result[$topic . '-' . $index] = new TopicAndPartition($topic, $index);
            }
        }

        return $result;
    }

    private function sortPartitions(ObjectKeyArray $partition2AllPotentialConsumers): array
    {
        /** @var TopicAndPartition[] $keys */
        $keys = $sortKeys = $partition2AllPotentialConsumers->getKeys();
        $values = $partition2AllPotentialConsumers->getValues();
        uksort($sortKeys, function (string $index1, string $index2) use ($keys, $values) {
            $key1 = $keys[$index1];
            $key2 = $keys[$index2];
            $ret = \count($values[$index1]) - \count($values[$index2]);
            if (0 === $ret) {
                $ret = substr_compare($key1->getTopic(), $key2->getTopic(), 0);
                if (0 === $ret) {
                    $ret = $key1->getPartition() - $key2->getPartition();
                }
            }

            return $ret;
        });

        return $sortKeys;
    }

    private function assignPartition(TopicPartition $partition, TreeSet $sortedCurrentSubscriptions, array &$currentAssignment, array &$consumer2AllPotentialPartitions, ObjectKeyArray $currentPartitionConsumer): void
    {
        foreach ($sortedCurrentSubscriptions as $consumer) {
            if (\in_array($partition, $consumer2AllPotentialPartitions[$consumer])) {
                unset($sortedCurrentSubscriptions[$consumer]);
                $currentAssignment[$consumer][] = $partition;
                $currentPartitionConsumer[$partition] = $consumer;
                // @phpstan-ignore-next-line
                $sortedCurrentSubscriptions->put([
                    $consumer,
                ]);
                break;
            }
        }
    }

    private function canParticipateInReassignment2(TopicPartition $partition, ObjectKeyArray $partition2AllPotentialConsumers): bool
    {
        // if a partition has two or more potential consumers it is subject to reassignment.
        return \count($partition2AllPotentialConsumers[$partition]) > 2;
    }

    private function canParticipateInReassignment4(string $consumer, array $currentAssignment, array $consumer2AllPotentialPartitions, ObjectKeyArray $partition2AllPotentialConsumers): bool
    {
        $currentPartitions = $currentAssignment[$consumer];
        $currentAssignmentSize = \count($currentPartitions);
        $maxAssignmentSize = \count($consumer2AllPotentialPartitions[$consumer]);
        if ($currentAssignmentSize < $maxAssignmentSize) {
            // if a consumer is not assigned all its potential partitions it is subject to reassignment
            return true;
        }
        foreach ($currentPartitions as $partition) {
            // if any of the partitions assigned to a consumer is subject to reassignment the consumer itself is subject to reassignment
            if ($this->canParticipateInReassignment2($partition, $partition2AllPotentialConsumers)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param TopicPartition[] $reassignablePartitions
     */
    private function performReassignments(array &$reassignablePartitions, array &$currentAssignment, ObjectKeyArray $prevAssignment, TreeSet $sortedCurrentSubscriptions, array &$consumer2AllPotentialPartitions, ObjectKeyArray $partition2AllPotentialConsumers, ObjectKeyArray $currentPartitionConsumer): bool
    {
        $reassignmentPerformed = $modified = false;

        // repeat reassignment until no partition can be moved to improve the balance
        do {
            $modified = false;
            // reassign all reassignable partitions (starting from the partition with least potential consumers and if needed) until the full list is processed or a balance is achieved
            foreach ($reassignablePartitions as $partition) {
                if (!$this->isBalanced($currentAssignment, $sortedCurrentSubscriptions, $consumer2AllPotentialPartitions)) {
                    break;
                }
                $consumer = $currentPartitionConsumer[$partition];

                if (isset($prevAssignment[$consumer]) && \count($currentAssignment[$consumer]) > \count($currentAssignment[$prevAssignment[$partition]->getConsumer()]) + 1) {
                    $this->reassignPartition2($partition, $currentAssignment, $sortedCurrentSubscriptions, $currentPartitionConsumer, $prevAssignment[$partition]->getConsumer());
                    $reassignmentPerformed = $modified = true;
                    continue;
                }

                // check if a better-suited consumer exist for the partition; if so, reassign it
                foreach ($partition2AllPotentialConsumers[$partition] as $otherConsumer) {
                    if (\count($currentAssignment[$consumer]) > \count($currentAssignment[$otherConsumer]) + 1) {
                        $this->reassignPartition1($partition, $currentAssignment, $sortedCurrentSubscriptions, $currentPartitionConsumer, $consumer2AllPotentialPartitions);
                        $reassignmentPerformed = $modified = true;
                        break;
                    }
                }
            }
        } while ($modified);

        return $reassignmentPerformed;
    }

    private function getBalanceScore(array $assignment): int
    {
        $score = 0;
        $consumer2AssignmentSize = [];
        foreach ($assignment as $key => $entry) {
            $consumer2AssignmentSize[$key] = \count($entry);
        }

        if (false !== reset($consumer2AssignmentSize)) {
            do {
                $consumerAssignmentSize = current($consumer2AssignmentSize);
                foreach ($consumer2AssignmentSize as $otherEntry) {
                    $score += abs($consumerAssignmentSize - $otherEntry);
                }
            } while (false !== next($consumer2AssignmentSize));
        }

        return $score;
    }

    private function isBalanced(array $currentAssignment, TreeSet $sortedCurrentSubscriptions, array $allSubscriptions): bool
    {
        $min = \count($currentAssignment[$sortedCurrentSubscriptions->first()]);
        $max = \count($currentAssignment[$sortedCurrentSubscriptions->last()]);
        if ($min >= $max - 1) {
            // if minimum and maximum numbers of partitions assigned to consumers differ by at most one return true
            return true;
        }

        // create a mapping from partitions to the consumer assigned to them
        $allPartitions = [];
        foreach ($currentAssignment as $key => $topicPartitions) {
            foreach ($topicPartitions as $topicPartition) {
                $allPartitions[$topicPartition] = $key;
            }
        }

        // for each consumer that does not have all the topic partitions it can get make sure none of the topic partitions it could but did not get cannot be moved to it (because that would break the balance)
        foreach ($sortedCurrentSubscriptions as $consumer) {
            $consumerPartitions = $currentAssignment[$consumer];
            $consumerPartitionCount = \count($consumerPartitions);

            // skip if this consumer already has all the topic partitions it can get
            if ($consumerPartitionCount == \count($allSubscriptions[$consumer])) {
                continue;
            }

            // otherwise make sure it cannot get any more
            $potentialTopicPartitions = $allSubscriptions[$consumer];
            foreach ($potentialTopicPartitions as $topicPartition) {
                if (!\in_array($topicPartition, $currentAssignment[$consumer])) {
                    $otherConsumer = $allPartitions[$topicPartition];
                    $otherConsumerPartitionCount = \count($currentAssignment[$otherConsumer]);
                    if ($consumerPartitionCount < $otherConsumerPartitionCount) {
                        return false;
                    }
                }
            }
        }

        return true;
    }

    private function reassignPartition1(TopicPartition $partition, array &$currentAssignment, TreeSet $sortedCurrentSubscriptions, ObjectKeyArray $currentPartitionConsumer, array &$consumer2AllPotentialPartitions): void
    {
        // find the new consumer
        $newConsumer = null;
        foreach ($sortedCurrentSubscriptions as $anotherConsumer) {
            if (\in_array($partition, $consumer2AllPotentialPartitions[$anotherConsumer])) {
                $newConsumer = $anotherConsumer;
                break;
            }
        }

        $this->reassignPartition2($partition, $currentAssignment, $sortedCurrentSubscriptions, $currentPartitionConsumer, $newConsumer);
    }

    private function reassignPartition2(TopicPartition $partition, array &$currentAssignment, TreeSet $sortedCurrentSubscriptions, ObjectKeyArray $currentPartitionConsumer, string $newConsumer): void
    {
        $consumer = $currentPartitionConsumer[$partition];
        // find the correct partition movement considering the stickiness requirement
        $partitionToBeMoved = $this->partitionMovements->getTheActualPartitionToBeMoved($partition, $consumer, $newConsumer);
        $this->processPartitionMovement($partitionToBeMoved, $newConsumer, $currentAssignment, $sortedCurrentSubscriptions, $currentPartitionConsumer);
    }

    private function processPartitionMovement(TopicPartition $partition, string $newConsumer, array &$currentAssignment, TreeSet $sortedCurrentSubscriptions, ObjectKeyArray $currentPartitionConsumer): void
    {
        $oldConsumer = $currentPartitionConsumer[$partition];

        unset($sortedCurrentSubscriptions[$oldConsumer], $sortedCurrentSubscriptions[$newConsumer]);

        $this->partitionMovements->movePartition($partition, $oldConsumer, $newConsumer);

        $index = array_search($partition, $currentAssignment[$oldConsumer]);
        if (false !== $index) {
            unset($currentAssignment[$oldConsumer][$index]);
        }
        $currentAssignment[$newConsumer][] = $partition;

        $currentPartitionConsumer[$partition] = $newConsumer;

        // @phpstan-ignore-next-line
        $sortedCurrentSubscriptions->put([
            $newConsumer,
            $oldConsumer,
        ]);
    }
}
