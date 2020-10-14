<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\AddOffsetsToTxn;

use longlang\phpkafka\Protocol\AbstractRequest;
use longlang\phpkafka\Protocol\ProtocolField;

class AddOffsetsToTxnRequest extends AbstractRequest
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
     * The unique group identifier.
     *
     * @var string
     */
    protected $groupId = '';

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('transactionalId', 'string', false, [0, 1], [], [], [], null),
                new ProtocolField('producerId', 'int64', false, [0, 1], [], [], [], null),
                new ProtocolField('producerEpoch', 'int16', false, [0, 1], [], [], [], null),
                new ProtocolField('groupId', 'string', false, [0, 1], [], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 25;
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

    public function getGroupId(): string
    {
        return $this->groupId;
    }

    public function setGroupId(string $groupId): self
    {
        $this->groupId = $groupId;

        return $this;
    }
}
