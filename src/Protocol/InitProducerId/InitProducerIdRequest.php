<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\InitProducerId;

use longlang\phpkafka\Protocol\AbstractRequest;
use longlang\phpkafka\Protocol\ProtocolField;

class InitProducerIdRequest extends AbstractRequest
{
    /**
     * The transactional id, or null if the producer is not transactional.
     *
     * @var string|null
     */
    protected $transactionalId = null;

    /**
     * The time in ms to wait before aborting idle transactions sent by this producer. This is only relevant if a TransactionalId has been defined.
     *
     * @var int
     */
    protected $transactionTimeoutMs = 0;

    /**
     * The producer id. This is used to disambiguate requests if a transactional id is reused following its expiration.
     *
     * @var int
     */
    protected $producerId = -1;

    /**
     * The producer's current epoch. This will be checked against the producer epoch on the broker, and the request will return an error if they do not match.
     *
     * @var int
     */
    protected $producerEpoch = -1;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('transactionalId', 'string', false, [0, 1, 2, 3], [2, 3], [0, 1, 2, 3], [], null),
                new ProtocolField('transactionTimeoutMs', 'int32', false, [0, 1, 2, 3], [2, 3], [], [], null),
                new ProtocolField('producerId', 'int64', false, [3], [2, 3], [], [], null),
                new ProtocolField('producerEpoch', 'int16', false, [3], [2, 3], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 22;
    }

    public function getMaxSupportedVersion(): int
    {
        return 3;
    }

    public function getFlexibleVersions(): array
    {
        return [2, 3];
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

    public function getTransactionTimeoutMs(): int
    {
        return $this->transactionTimeoutMs;
    }

    public function setTransactionTimeoutMs(int $transactionTimeoutMs): self
    {
        $this->transactionTimeoutMs = $transactionTimeoutMs;

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
}
