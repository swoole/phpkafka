<?php

declare(strict_types=1);

namespace longlang\phpkafka\Producer;

class ProduceMessage
{
    /**
     * @var string
     */
    protected $topic;

    /**
     * @var string|null
     */
    protected $value;

    /**
     * @var string|null
     */
    protected $key;

    /**
     * @var array
     */
    protected $headers;

    /**
     * @var int
     */
    protected $partitionIndex;

    public function __construct(string $topic, ?string $value, ?string $key = null, array $headers = [], int $partitionIndex = 0)
    {
        $this->topic = $topic;
        $this->value = $value;
        $this->key = $key;
        $this->headers = $headers;
        $this->partitionIndex = $partitionIndex;
    }

    /**
     * @return string
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * @return string|null
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string|null
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @return int
     */
    public function getPartitionIndex()
    {
        return $this->partitionIndex;
    }
}
