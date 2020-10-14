<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\EndTxn;

use longlang\phpkafka\Protocol\AbstractRequest;
use longlang\phpkafka\Protocol\ProtocolField;

class EndTxnRequest extends AbstractRequest
{
    /**
     * The ID of the transaction to end.
     *
     * @var string
     */
    protected $transactionalId = '';

    /**
     * The producer ID.
     *
     * @var int
     */
    protected $producerId = 0;

    /**
     * The current epoch associated with the producer.
     *
     * @var int
     */
    protected $producerEpoch = 0;

    /**
     * True if the transaction was committed, false if it was aborted.
     *
     * @var bool
     */
    protected $committed = false;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('transactionalId', 'string', false, [0, 1], [], [], [], null),
                new ProtocolField('producerId', 'int64', false, [0, 1], [], [], [], null),
                new ProtocolField('producerEpoch', 'int16', false, [0, 1], [], [], [], null),
                new ProtocolField('committed', 'bool', false, [0, 1], [], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 26;
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

    public function getCommitted(): bool
    {
        return $this->committed;
    }

    public function setCommitted(bool $committed): self
    {
        $this->committed = $committed;

        return $this;
    }
}
