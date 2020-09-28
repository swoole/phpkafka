<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\Fetch;

use Longyan\Kafka\Protocol\AbstractStruct;
use Longyan\Kafka\Protocol\ProtocolField;

class ForgottenTopic extends AbstractStruct
{
    /**
     * The partition name.
     *
     * @var string
     */
    protected $topicName;

    /**
     * The partitions indexes to forget.
     *
     * @var int32[]
     */
    protected $forgottenPartitionIndexes = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('topicName', 'string', false, [7, 8, 9, 10, 11], [], [], [], null),
                new ProtocolField('forgottenPartitionIndexes', 'int32', true, [7, 8, 9, 10, 11], [], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [];
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

    /**
     * @return int32[]
     */
    public function getForgottenPartitionIndexes(): array
    {
        return $this->forgottenPartitionIndexes;
    }

    /**
     * @param int32[] $forgottenPartitionIndexes
     */
    public function setForgottenPartitionIndexes(array $forgottenPartitionIndexes): self
    {
        $this->forgottenPartitionIndexes = $forgottenPartitionIndexes;

        return $this;
    }
}
