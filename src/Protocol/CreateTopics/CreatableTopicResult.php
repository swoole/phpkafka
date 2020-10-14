<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\CreateTopics;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolField;

class CreatableTopicResult extends AbstractStruct
{
    /**
     * The topic name.
     *
     * @var string
     */
    protected $name = '';

    /**
     * The error code, or 0 if there was no error.
     *
     * @var int
     */
    protected $errorCode = 0;

    /**
     * The error message, or null if there was no error.
     *
     * @var string|null
     */
    protected $errorMessage = null;

    /**
     * Optional topic config error returned if configs are not returned in the response.
     *
     * @var int
     */
    protected $topicConfigErrorCode = 0;

    /**
     * Number of partitions of the topic.
     *
     * @var int
     */
    protected $numPartitions = -1;

    /**
     * Replication factor of the topic.
     *
     * @var int
     */
    protected $replicationFactor = -1;

    /**
     * Configuration of the topic.
     *
     * @var CreatableTopicConfigs[]|null
     */
    protected $configs = null;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('name', 'string', false, [0, 1, 2, 3, 4, 5], [5], [], [], null),
                new ProtocolField('errorCode', 'int16', false, [0, 1, 2, 3, 4, 5], [5], [], [], null),
                new ProtocolField('errorMessage', 'string', false, [1, 2, 3, 4, 5], [5], [0, 1, 2, 3, 4, 5], [], null),
                new ProtocolField('numPartitions', 'int32', false, [5], [5], [], [], null),
                new ProtocolField('replicationFactor', 'int16', false, [5], [5], [], [], null),
                new ProtocolField('configs', CreatableTopicConfigs::class, true, [5], [5], [5], [], null),
            ];
            self::$taggedFieldses[self::class] = [
                new ProtocolField('topicConfigErrorCode', 'int16', false, [5], [5], [], [5], 0),
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [5];
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

    public function getErrorCode(): int
    {
        return $this->errorCode;
    }

    public function setErrorCode(int $errorCode): self
    {
        $this->errorCode = $errorCode;

        return $this;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    public function setErrorMessage(?string $errorMessage): self
    {
        $this->errorMessage = $errorMessage;

        return $this;
    }

    public function getTopicConfigErrorCode(): int
    {
        return $this->topicConfigErrorCode;
    }

    public function setTopicConfigErrorCode(int $topicConfigErrorCode): self
    {
        $this->topicConfigErrorCode = $topicConfigErrorCode;

        return $this;
    }

    public function getNumPartitions(): int
    {
        return $this->numPartitions;
    }

    public function setNumPartitions(int $numPartitions): self
    {
        $this->numPartitions = $numPartitions;

        return $this;
    }

    public function getReplicationFactor(): int
    {
        return $this->replicationFactor;
    }

    public function setReplicationFactor(int $replicationFactor): self
    {
        $this->replicationFactor = $replicationFactor;

        return $this;
    }

    /**
     * @return CreatableTopicConfigs[]|null
     */
    public function getConfigs(): ?array
    {
        return $this->configs;
    }

    /**
     * @param CreatableTopicConfigs[]|null $configs
     */
    public function setConfigs(?array $configs): self
    {
        $this->configs = $configs;

        return $this;
    }
}
