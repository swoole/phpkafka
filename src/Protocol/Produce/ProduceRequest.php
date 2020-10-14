<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\Produce;

use longlang\phpkafka\Protocol\AbstractRequest;
use longlang\phpkafka\Protocol\ProtocolField;

class ProduceRequest extends AbstractRequest
{
    /**
     * The transactional ID, or null if the producer is not transactional.
     *
     * @var string|null
     */
    protected $transactionalId = null;

    /**
     * The number of acknowledgments the producer requires the leader to have received before considering a request complete. Allowed values: 0 for no acknowledgments, 1 for only the leader and -1 for the full ISR.
     *
     * @var int
     */
    protected $acks = 0;

    /**
     * The timeout to await a response in miliseconds.
     *
     * @var int
     */
    protected $timeoutMs = 0;

    /**
     * Each topic to produce to.
     *
     * @var TopicProduceData[]
     */
    protected $topics = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('transactionalId', 'string', false, [3, 4, 5, 6, 7, 8], [], [0, 1, 2, 3, 4, 5, 6, 7, 8], [], null),
                new ProtocolField('acks', 'int16', false, [0, 1, 2, 3, 4, 5, 6, 7, 8], [], [], [], null),
                new ProtocolField('timeoutMs', 'int32', false, [0, 1, 2, 3, 4, 5, 6, 7, 8], [], [], [], null),
                new ProtocolField('topics', TopicProduceData::class, true, [0, 1, 2, 3, 4, 5, 6, 7, 8], [], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 0;
    }

    public function getMaxSupportedVersion(): int
    {
        return 8;
    }

    public function getFlexibleVersions(): array
    {
        return [];
    }

    public function getTransactionalId(): ?string
    {
        return $this->transactionalId;
    }

    public function setTransactionalId(?string $transactionalId): self
    {
        $this->transactionalId = $transactionalId;

        return $this;
    }

    public function getAcks(): int
    {
        return $this->acks;
    }

    public function setAcks(int $acks): self
    {
        $this->acks = $acks;

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

    /**
     * @return TopicProduceData[]
     */
    public function getTopics(): array
    {
        return $this->topics;
    }

    /**
     * @param TopicProduceData[] $topics
     */
    public function setTopics(array $topics): self
    {
        $this->topics = $topics;

        return $this;
    }
}
