<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\CreateTopics;

use Longyan\Kafka\Protocol\AbstractRequest;
use Longyan\Kafka\Protocol\ApiKeys;
use Longyan\Kafka\Protocol\ProtocolField;

class CreateTopicsRequest extends AbstractRequest
{
    /**
     * The topics to create.
     *
     * @var Topic[]
     */
    protected $topics = [];

    /**
     * How long to wait in milliseconds before timing out the request.
     *
     * @var int
     */
    protected $timeoutMs = 0;

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
                new ProtocolField('topics', Topic::class, 'CompactArray', 5),
                new ProtocolField('topics', Topic::class, 'ArrayInt32', 0),
                new ProtocolField('timeoutMs', 'Int32', null, 0),
                new ProtocolField('validateOnly', 'Boolean', null, 1),
            ];
            self::$taggedFieldses[self::class] = [];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return ApiKeys::PROTOCOL_CREATE_TOPICS;
    }

    public function getMaxSupportedVersion(): int
    {
        return 5;
    }

    public function getFlexibleVersions(): ?int
    {
        return 5;
    }

    /**
     * @return Topic[]
     */
    public function getTopics(): array
    {
        return $this->topics;
    }

    /**
     * @param Topic[]
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
