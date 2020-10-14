<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\CreateTopics;

use longlang\phpkafka\Protocol\AbstractRequest;
use longlang\phpkafka\Protocol\ProtocolField;

class CreateTopicsRequest extends AbstractRequest
{
    /**
     * The topics to create.
     *
     * @var CreatableTopic[]
     */
    protected $topics = [];

    /**
     * How long to wait in milliseconds before timing out the request.
     *
     * @var int
     */
    protected $timeoutMs = 60000;

    /**
     * If true, check that the topics can be created as specified, but don't create anything.
     *
     * @var bool
     */
    protected $validateOnly = false;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('topics', CreatableTopic::class, true, [0, 1, 2, 3, 4, 5], [5], [], [], null),
                new ProtocolField('timeoutMs', 'int32', false, [0, 1, 2, 3, 4, 5], [5], [], [], null),
                new ProtocolField('validateOnly', 'bool', false, [1, 2, 3, 4, 5], [5], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 19;
    }

    public function getMaxSupportedVersion(): int
    {
        return 5;
    }

    public function getFlexibleVersions(): array
    {
        return [5];
    }

    /**
     * @return CreatableTopic[]
     */
    public function getTopics(): array
    {
        return $this->topics;
    }

    /**
     * @param CreatableTopic[] $topics
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
