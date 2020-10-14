<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\Metadata;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolField;

class MetadataResponseTopic extends AbstractStruct
{
    /**
     * The topic error, or 0 if there was no error.
     *
     * @var int
     */
    protected $errorCode = 0;

    /**
     * The topic name.
     *
     * @var string
     */
    protected $name = '';

    /**
     * True if the topic is internal.
     *
     * @var bool
     */
    protected $isInternal = false;

    /**
     * Each partition in the topic.
     *
     * @var MetadataResponsePartition[]
     */
    protected $partitions = [];

    /**
     * 32-bit bitfield to represent authorized operations for this topic.
     *
     * @var int
     */
    protected $topicAuthorizedOperations = -2147483648;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('errorCode', 'int16', false, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9], [9], [], [], null),
                new ProtocolField('name', 'string', false, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9], [9], [], [], null),
                new ProtocolField('isInternal', 'bool', false, [1, 2, 3, 4, 5, 6, 7, 8, 9], [9], [], [], null),
                new ProtocolField('partitions', MetadataResponsePartition::class, true, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9], [9], [], [], null),
                new ProtocolField('topicAuthorizedOperations', 'int32', false, [8, 9], [9], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [9];
    }

    public function getErrorCode(): int
    {
        return $this->errorCode;
    }

    public function setErrorCode(int $errorCode): self
    {
        $this->errorCode = $errorCode;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getIsInternal(): bool
    {
        return $this->isInternal;
    }

    public function setIsInternal(bool $isInternal): self
    {
        $this->isInternal = $isInternal;

        return $this;
    }

    /**
     * @return MetadataResponsePartition[]
     */
    public function getPartitions(): array
    {
        return $this->partitions;
    }

    /**
     * @param MetadataResponsePartition[] $partitions
     */
    public function setPartitions(array $partitions): self
    {
        $this->partitions = $partitions;

        return $this;
    }

    public function getTopicAuthorizedOperations(): int
    {
        return $this->topicAuthorizedOperations;
    }

    public function setTopicAuthorizedOperations(int $topicAuthorizedOperations): self
    {
        $this->topicAuthorizedOperations = $topicAuthorizedOperations;

        return $this;
    }
}
