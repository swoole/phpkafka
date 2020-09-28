<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\AddPartitionsToTxn;

use Longyan\Kafka\Protocol\AbstractStruct;
use Longyan\Kafka\Protocol\ProtocolField;

class AddPartitionsToTxnTopicResult extends AbstractStruct
{
    /**
     * The topic name.
     *
     * @var string
     */
    protected $topicName;

    /**
     * The results for each partition.
     *
     * @var AddPartitionsToTxnPartitionResult[]
     */
    protected $results = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('topicName', 'string', false, [0, 1], [], [], [], null),
                new ProtocolField('results', AddPartitionsToTxnPartitionResult::class, true, [0, 1], [], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [];
    }

    public function getTopicName(): string
    {
        return $this->topicName;
    }

    public function setTopicName(string $topicName): self
    {
        $this->topicName = $topicName;

        return $this;
    }

    /**
     * @return AddPartitionsToTxnPartitionResult[]
     */
    public function getResults(): array
    {
        return $this->results;
    }

    /**
     * @param AddPartitionsToTxnPartitionResult[] $results
     */
    public function setResults(array $results): self
    {
        $this->results = $results;

        return $this;
    }
}
