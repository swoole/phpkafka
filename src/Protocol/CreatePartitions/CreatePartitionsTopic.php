<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\CreatePartitions;

use Longyan\Kafka\Protocol\AbstractStruct;
use Longyan\Kafka\Protocol\ProtocolField;

class CreatePartitionsTopic extends AbstractStruct
{
    /**
     * The topic name.
     *
     * @var string
     */
    protected $topicName;

    /**
     * The new partition count.
     *
     * @var int
     */
    protected $count;

    /**
     * The new partition assignments.
     *
     * @var CreatePartitionsAssignment[]|null
     */
    protected $assignments;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('topicName', 'string', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('count', 'int32', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('assignments', CreatePartitionsAssignment::class, true, [0, 1, 2], [2], [0, 1, 2], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [2];
    }

    public function getTopicName(): string
    {
        return $this->topicName;
    }

    public function setTopicName(string $topicName): self
    {
        $this->topicName = $topicName;

        return $this;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function setCount(int $count): self
    {
        $this->count = $count;

        return $this;
    }

    /**
     * @return CreatePartitionsAssignment[]|null
     */
    public function getAssignments(): ?array
    {
        return $this->assignments;
    }

    /**
     * @param CreatePartitionsAssignment[]|null $assignments
     */
    public function setAssignments(?array $assignments): self
    {
        $this->assignments = $assignments;

        return $this;
    }
}
