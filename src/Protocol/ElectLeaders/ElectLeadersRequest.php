<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\ElectLeaders;

use longlang\phpkafka\Protocol\AbstractRequest;
use longlang\phpkafka\Protocol\ProtocolField;

class ElectLeadersRequest extends AbstractRequest
{
    /**
     * Type of elections to conduct for the partition. A value of '0' elects the preferred replica. A value of '1' elects the first live replica if there are no in-sync replica.
     *
     * @var int
     */
    protected $electionType = 0;

    /**
     * The topic partitions to elect leaders.
     *
     * @var TopicPartitions[]|null
     */
    protected $topicPartitions = null;

    /**
     * The time in ms to wait for the election to complete.
     *
     * @var int
     */
    protected $timeoutMs = 60000;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('electionType', 'int8', false, [1, 2], [2], [], [], null),
                new ProtocolField('topicPartitions', TopicPartitions::class, true, [0, 1, 2], [2], [0, 1, 2], [], null),
                new ProtocolField('timeoutMs', 'int32', false, [0, 1, 2], [2], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 43;
    }

    public function getMaxSupportedVersion(): int
    {
        return 2;
    }

    public function getFlexibleVersions(): array
    {
        return [2];
    }

    public function getElectionType(): int
    {
        return $this->electionType;
    }

    public function setElectionType(int $electionType): self
    {
        $this->electionType = $electionType;

        return $this;
    }

    /**
     * @return TopicPartitions[]|null
     */
    public function getTopicPartitions(): ?array
    {
        return $this->topicPartitions;
    }

    /**
     * @param TopicPartitions[]|null $topicPartitions
     */
    public function setTopicPartitions(?array $topicPartitions): self
    {
        $this->topicPartitions = $topicPartitions;

        return $this;
    }

    public function getTimeoutMs(): int
    {
        return $this->timeoutMs;
    }

    public function setTimeoutMs(int $timeoutMs): self
    {
        $this->timeoutMs = $timeoutMs;

        return $this;
    }
}
