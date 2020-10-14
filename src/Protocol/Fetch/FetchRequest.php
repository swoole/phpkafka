<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\Fetch;

use longlang\phpkafka\Protocol\AbstractRequest;
use longlang\phpkafka\Protocol\ProtocolField;

class FetchRequest extends AbstractRequest
{
    /**
     * The broker ID of the follower, of -1 if this request is from a consumer.
     *
     * @var int
     */
    protected $replicaId = 0;

    /**
     * The maximum time in milliseconds to wait for the response.
     *
     * @var int
     */
    protected $maxWait = 0;

    /**
     * The minimum bytes to accumulate in the response.
     *
     * @var int
     */
    protected $minBytes = 0;

    /**
     * The maximum bytes to fetch.  See KIP-74 for cases where this limit may not be honored.
     *
     * @var int
     */
    protected $maxBytes = 0x7fffffff;

    /**
     * This setting controls the visibility of transactional records. Using READ_UNCOMMITTED (isolation_level = 0) makes all records visible. With READ_COMMITTED (isolation_level = 1), non-transactional and COMMITTED transactional records are visible. To be more concrete, READ_COMMITTED returns all data from offsets smaller than the current LSO (last stable offset), and enables the inclusion of the list of aborted transactions in the result, which allows consumers to discard ABORTED transactional records.
     *
     * @var int
     */
    protected $isolationLevel = 0;

    /**
     * The fetch session ID.
     *
     * @var int
     */
    protected $sessionId = 0;

    /**
     * The epoch of the partition leader as known to the follower replica or a consumer.
     *
     * @var int
     */
    protected $epoch = -1;

    /**
     * The topics to fetch.
     *
     * @var FetchableTopic[]
     */
    protected $topics = [];

    /**
     * In an incremental fetch request, the partitions to remove.
     *
     * @var ForgottenTopic[]
     */
    protected $forgotten = [];

    /**
     * Rack ID of the consumer making this request.
     *
     * @var string
     */
    protected $rackId = '';

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('replicaId', 'int32', false, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11], [], [], [], null),
                new ProtocolField('maxWait', 'int32', false, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11], [], [], [], null),
                new ProtocolField('minBytes', 'int32', false, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11], [], [], [], null),
                new ProtocolField('maxBytes', 'int32', false, [3, 4, 5, 6, 7, 8, 9, 10, 11], [], [], [], null),
                new ProtocolField('isolationLevel', 'int8', false, [4, 5, 6, 7, 8, 9, 10, 11], [], [], [], null),
                new ProtocolField('sessionId', 'int32', false, [7, 8, 9, 10, 11], [], [], [], null),
                new ProtocolField('epoch', 'int32', false, [7, 8, 9, 10, 11], [], [], [], null),
                new ProtocolField('topics', FetchableTopic::class, true, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11], [], [], [], null),
                new ProtocolField('forgotten', ForgottenTopic::class, true, [7, 8, 9, 10, 11], [], [], [], null),
                new ProtocolField('rackId', 'string', false, [11], [], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 1;
    }

    public function getMaxSupportedVersion(): int
    {
        return 11;
    }

    public function getFlexibleVersions(): array
    {
        return [];
    }

    public function getReplicaId(): int
    {
        return $this->replicaId;
    }

    public function setReplicaId(int $replicaId): self
    {
        $this->replicaId = $replicaId;

        return $this;
    }

    public function getMaxWait(): int
    {
        return $this->maxWait;
    }

    public function setMaxWait(int $maxWait): self
    {
        $this->maxWait = $maxWait;

        return $this;
    }

    public function getMinBytes(): int
    {
        return $this->minBytes;
    }

    public function setMinBytes(int $minBytes): self
    {
        $this->minBytes = $minBytes;

        return $this;
    }

    public function getMaxBytes(): int
    {
        return $this->maxBytes;
    }

    public function setMaxBytes(int $maxBytes): self
    {
        $this->maxBytes = $maxBytes;

        return $this;
    }

    public function getIsolationLevel(): int
    {
        return $this->isolationLevel;
    }

    public function setIsolationLevel(int $isolationLevel): self
    {
        $this->isolationLevel = $isolationLevel;

        return $this;
    }

    public function getSessionId(): int
    {
        return $this->sessionId;
    }

    public function setSessionId(int $sessionId): self
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    public function getEpoch(): int
    {
        return $this->epoch;
    }

    public function setEpoch(int $epoch): self
    {
        $this->epoch = $epoch;

        return $this;
    }

    /**
     * @return FetchableTopic[]
     */
    public function getTopics(): array
    {
        return $this->topics;
    }

    /**
     * @param FetchableTopic[] $topics
     */
    public function setTopics(array $topics): self
    {
        $this->topics = $topics;

        return $this;
    }

    /**
     * @return ForgottenTopic[]
     */
    public function getForgotten(): array
    {
        return $this->forgotten;
    }

    /**
     * @param ForgottenTopic[] $forgotten
     */
    public function setForgotten(array $forgotten): self
    {
        $this->forgotten = $forgotten;

        return $this;
    }

    public function getRackId(): string
    {
        return $this->rackId;
    }

    public function setRackId(string $rackId): self
    {
        $this->rackId = $rackId;

        return $this;
    }
}
