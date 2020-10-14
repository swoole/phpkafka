<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\AddPartitionsToTxn;

use longlang\phpkafka\Protocol\AbstractRequest;
use longlang\phpkafka\Protocol\ProtocolField;

class AddPartitionsToTxnRequest extends AbstractRequest
{
    /**
     * The transactional id corresponding to the transaction.
     *
     * @var string
     */
    protected $transactionalId = '';

    /**
     * Current producer id in use by the transactional id.
     *
     * @var int
     */
    protected $producerId = 0;

    /**
     * Current epoch associated with the producer id.
     *
     * @var int
     */
    protected $producerEpoch = 0;

    /**
     * The partitions to add to the transaction.
     *
     * @var AddPartitionsToTxnTopic[]
     */
    protected $topics = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('transactionalId', 'string', false, [0, 1], [], [], [], null),
                new ProtocolField('producerId', 'int64', false, [0, 1], [], [], [], null),
                new ProtocolField('producerEpoch', 'int16', false, [0, 1], [], [], [], null),
                new ProtocolField('topics', AddPartitionsToTxnTopic::class, true, [0, 1], [], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 24;
    }

    public function getMaxSupportedVersion(): int
    {
        return 1;
    }

    public function getFlexibleVersions(): array
    {
        return [];
    }

    public function getTransactionalId(): string
    {
        return $this->transactionalId;
    }

    public function setTransactionalId(string $transactionalId): self
    {
        $this->transactionalId = $transactionalId;

        return $this;
    }

    public function getProducerId(): int
    {
        return $this->producerId;
    }

    public function setProducerId(int $producerId): self
    {
        $this->producerId = $producerId;

        return $this;
    }

    public function getProducerEpoch(): int
    {
        return $this->producerEpoch;
    }

    public function setProducerEpoch(int $producerEpoch): self
    {
        $this->producerEpoch = $producerEpoch;

        return $this;
    }

    /**
     * @return AddPartitionsToTxnTopic[]
     */
    public function getTopics(): array
    {
        return $this->topics;
    }

    /**
     * @param AddPartitionsToTxnTopic[] $topics
     */
    public function setTopics(array $topics): self
    {
        $this->topics = $topics;

        return $this;
    }
}
