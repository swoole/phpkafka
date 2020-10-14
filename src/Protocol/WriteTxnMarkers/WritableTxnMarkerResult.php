<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\WriteTxnMarkers;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolField;

class WritableTxnMarkerResult extends AbstractStruct
{
    /**
     * The current producer ID in use by the transactional ID.
     *
     * @var int
     */
    protected $producerId = 0;

    /**
     * The results by topic.
     *
     * @var WritableTxnMarkerTopicResult[]
     */
    protected $topics = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('producerId', 'int64', false, [0], [], [], [], null),
                new ProtocolField('topics', WritableTxnMarkerTopicResult::class, true, [0], [], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [];
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

    /**
     * @return WritableTxnMarkerTopicResult[]
     */
    public function getTopics(): array
    {
        return $this->topics;
    }

    /**
     * @param WritableTxnMarkerTopicResult[] $topics
     */
    public function setTopics(array $topics): self
    {
        $this->topics = $topics;

        return $this;
    }
}
