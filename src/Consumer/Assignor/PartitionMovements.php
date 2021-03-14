<?php

declare(strict_types=1);

namespace longlang\phpkafka\Consumer\Assignor;

use longlang\phpkafka\Consumer\Struct\ConsumerPair;
use longlang\phpkafka\Consumer\Struct\TopicPartition;
use longlang\phpkafka\Util\ArrayUtil;
use longlang\phpkafka\Util\ObjectKeyArray;

class PartitionMovements
{
    /**
     * @var array
     */
    private $partitionMovementsByTopic = [];

    /**
     * @var ObjectKeyArray array<TopicPartition, ConsumerPair>
     */
    private $partitionMovements;

    public function __construct()
    {
        $this->partitionMovements = new ObjectKeyArray();
    }

    public function removeMovementRecordOfPartition(TopicPartition $partition): ConsumerPair
    {
        $pair = $this->partitionMovements[$partition];
        unset($this->partitionMovements[$partition]);

        $topic = $partition->getTopic();
        $partitionMovementsForThisTopic = $this->partitionMovementsByTopic[$topic];
        unset($partitionMovementsForThisTopic[$pair][$partition]);
        if (empty($partitionMovementsForThisTopic[$pair])) {
            unset($partitionMovementsForThisTopic[$pair]);
        }
        if (empty($this->partitionMovementsByTopic[$topic])) {
            unset($this->partitionMovementsByTopic[$topic]);
        }

        return $pair;
    }

    public function addPartitionMovementRecord(TopicPartition $partition, ConsumerPair $pair): void
    {
        $this->partitionMovements[$partition] = $pair;
        $topic = $partition->getTopic();
        if (!isset($this->partitionMovementsByTopic[$topic])) {
            $this->partitionMovementsByTopic[$topic] = new ObjectKeyArray();
        }

        $partitionMovementsForThisTopic = $this->partitionMovementsByTopic[$topic];
        $partitionMovementsForThisTopic[$pair][] = $partition;
    }

    public function movePartition(TopicPartition $partition, string $oldConsumer, string $newConsumer): void
    {
        $pair = new ConsumerPair($oldConsumer, $newConsumer);

        if (isset($this->partitionMovements[$partition])) {
            // this partition has previously moved
            $existingPair = $this->removeMovementRecordOfPartition($partition);
            if ($existingPair->getSrcMemberId() !== $newConsumer) {
                // the partition is not moving back to its previous consumer return new ConsumerPair2(existingPair.src, newConsumer);
                $this->addPartitionMovementRecord($partition, new ConsumerPair($existingPair->getSrcMemberId(), $newConsumer));
            }
        } else {
            $this->addPartitionMovementRecord($partition, $pair);
        }
    }

    public function getTheActualPartitionToBeMoved(TopicPartition $partition, string $oldConsumer, string $newConsumer): TopicPartition
    {
        $topic = $partition->getTopic();

        if (!isset($this->partitionMovementsByTopic[$topic])) {
            return $partition;
        }

        $partitionMovementsForThisTopic = $this->partitionMovementsByTopic[$topic];
        $reversePair = new ConsumerPair($newConsumer, $oldConsumer);
        if (!isset($partitionMovementsForThisTopic[$reversePair])) {
            return $partition;
        }
        $result = current($partitionMovementsForThisTopic[$reversePair]);
        next($partitionMovementsForThisTopic[$reversePair]);

        return $result;
    }

    /**
     * @param ConsumerPair[] $pairs
     */
    public function isLinked(string $src, string $dst, array $pairs, array &$currentPath): bool
    {
        if ($src === $dst) {
            return false;
        }
        if (0 === \count($currentPath)) {
            return false;
        }
        if ((new ConsumerPair($src, $dst))->in($pairs)) {
            $currentPath[] = $src;
            $currentPath[] = $dst;

            return true;
        }
        foreach ($pairs as $pair) {
            if ($pair->getSrcMemberId() === $src) {
                $reducedSet = $pairs;
                $index = array_search($pair, $reducedSet);
                unset($reducedSet[$index]);
                $currentPath[] = $pair->getSrcMemberId();

                return $this->isLinked($pair->getDstMemberId(), $dst, $reducedSet, $currentPath);
            }
        }

        return false;
    }

    public function in(array $cycle, array $cycles): bool
    {
        $superCycle = $cycle;
        array_pop($superCycle);
        foreach ($cycle as $value) {
            $superCycle[] = $value;
        }
        foreach ($cycles as $foundCycle) {
            if (\count($foundCycle) === \count($cycle) && -1 !== ArrayUtil::indexOfSubList($superCycle, $foundCycle)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param ConsumerPair[] $pairs
     *
     * @return bool
     */
    public function hasCycles(array $pairs)
    {
        $cycles = [];
        foreach ($pairs as $key => $pair) {
            $reducedPairs = $pairs;
            unset($reducedPairs[$key]);
            $path = [$pair->getSrcMemberId()];
            if ($this->isLinked($pair->getDstMemberId(), $pair->getSrcMemberId(), $reducedPairs, $path) && !$this->in($path, $cycles)) {
                $cycles[] = [$path];
            }
        }

        // for now we want to make sure there is no partition movements of the same topic between a pair of consumers.
        // the odds of finding a cycle among more than two consumers seem to be very low (according to various randomized tests with the given sticky algorithm) that it should not worth the added complexity of handling those cases.
        foreach ($cycles as $cycle) {
            // @phpstan-ignore-next-line
            if (3 === \count($cycle)) { // indicates a cycle of length 2
                return true;
            }
        }

        return false;
    }

    public function isSticky(): bool
    {
        foreach ($this->partitionMovementsByTopic as $topicMovements) {
            foreach ($topicMovements as $topicMovementPairs => $_) {
                if ($this->hasCycles($topicMovementPairs)) {
                    return false;
                }
            }
        }

        return true;
    }
}
