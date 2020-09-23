<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\CreateTopics;

use Longyan\Kafka\Protocol\AbstractStruct;
use Longyan\Kafka\Protocol\ProtocolField;

class TopicResult extends AbstractStruct
{
    /**
     * The topic name.
     *
     * @var string
     */
    protected $name;

    /**
     * The error code, or 0 if there was no error.
     *
     * @var int
     */
    protected $errorCode;

    /**
     * The error message, or null if there was no error.
     *
     * @var string|null
     */
    protected $errorMessage;

    /**
     * Number of partitions of the topic.
     *
     * @var int
     */
    protected $numPartitions;

    /**
     * Replication factor of the topic.
     *
     * @var int
     */
    protected $replicationFactor;

    /**
     * Configuration of the topic.
     *
     * @var ConfigResult[]
     */
    protected $configs;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('name', 'CompactString', null, 5),
                new ProtocolField('name', 'String16', null, 0),
                new ProtocolField('errorCode', 'Int16', null, 0),
                new ProtocolField('errorMessage', 'CompactNullableString', null, 5),
                new ProtocolField('errorMessage', 'NullableString', null, 1),
                new ProtocolField('numPartitions', 'Int32', null, 5),
                new ProtocolField('replicationFactor', 'Int16', null, 5),
                new ProtocolField('configs', ConfigResult::class, 'CompactArray', 5),
            ];
            self::$taggedFieldses[self::class] = [];
        }
    }

    public function getFlexibleVersions(): ?int
    {
        return 5;
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
     * @return ConfigResult[]
     */
    public function getConfigs(): array
    {
        return $this->configs;
    }

    /**
     * @param ConfigResult[] $configs configuration of the topic
     */
    public function setConfigs(array $configs): self
    {
        $this->configs = $configs;

        return $this;
    }
}
