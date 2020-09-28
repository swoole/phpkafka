<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\ListOffset;

use Longyan\Kafka\Protocol\AbstractRequest;
use Longyan\Kafka\Protocol\ProtocolField;

class ListOffsetRequest extends AbstractRequest
{
    /**
     * The broker ID of the requestor, or -1 if this request is being made by a normal consumer.
     *
     * @var int
     */
    protected $brokerId;

    /**
     * This setting controls the visibility of transactional records. Using READ_UNCOMMITTED (isolation_level = 0) makes all records visible. With READ_COMMITTED (isolation_level = 1), non-transactional and COMMITTED transactional records are visible. To be more concrete, READ_COMMITTED returns all data from offsets smaller than the current LSO (last stable offset), and enables the inclusion of the list of aborted transactions in the result, which allows consumers to discard ABORTED transactional records.
     *
     * @var int
     */
    protected $isolationLevel;

    /**
     * Each topic in the request.
     *
     * @var ListOffsetTopic[]
     */
    protected $topics = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('brokerId', 'int32', false, [0, 1, 2, 3, 4, 5], [], [], [], null),
                new ProtocolField('isolationLevel', 'int8', false, [2, 3, 4, 5], [], [], [], null),
                new ProtocolField('topics', ListOffsetTopic::class, true, [0, 1, 2, 3, 4, 5], [], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 2;
    }

    public function getMaxSupportedVersion(): int
    {
        return 5;
    }

    public function getFlexibleVersions(): array
    {
        return [];
    }

    public function getBrokerId(): int
    {
        return $this->brokerId;
    }

    public function setBrokerId(int $brokerId): self
    {
        $this->brokerId = $brokerId;

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

    /**
     * @return ListOffsetTopic[]
     */
    public function getTopics(): array
    {
        return $this->topics;
    }

    /**
     * @param ListOffsetTopic[] $topics
     */
    public function setTopics(array $topics): self
    {
        $this->topics = $topics;

        return $this;
    }
}
