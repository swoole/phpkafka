<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\CreatePartitions;

use longlang\phpkafka\Protocol\AbstractRequest;
use longlang\phpkafka\Protocol\ProtocolField;

class CreatePartitionsRequest extends AbstractRequest
{
    /**
     * Each topic that we want to create new partitions inside.
     *
     * @var CreatePartitionsTopic[]
     */
    protected $topics = [];

    /**
     * The time in ms to wait for the partitions to be created.
     *
     * @var int
     */
    protected $timeoutMs = 0;

    /**
     * If true, then validate the request, but don't actually increase the number of partitions.
     *
     * @var bool
     */
    protected $validateOnly = false;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('topics', CreatePartitionsTopic::class, true, [0, 1, 2], [2], [], [], null),
                new ProtocolField('timeoutMs', 'int32', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('validateOnly', 'bool', false, [0, 1, 2], [2], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 37;
    }

    public function getMaxSupportedVersion(): int
    {
        return 2;
    }

    public function getFlexibleVersions(): array
    {
        return [2];
    }

    /**
     * @return CreatePartitionsTopic[]
     */
    public function getTopics(): array
    {
        return $this->topics;
    }

    /**
     * @param CreatePartitionsTopic[] $topics
     */
    public function setTopics(array $topics): self
    {
        $this->topics = $topics;

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

    public function getValidateOnly(): bool
    {
        return $this->validateOnly;
    }

    public function setValidateOnly(bool $validateOnly): self
    {
        $this->validateOnly = $validateOnly;

        return $this;
    }
}
