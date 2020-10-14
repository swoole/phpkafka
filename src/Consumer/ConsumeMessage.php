<?php

declare(strict_types=1);

namespace longlang\phpkafka\Consumer;

class ConsumeMessage
{
    /**
     * @var Consumer
     */
    protected $consumer;

    /**
     * @var string
     */
    protected $topic;

    /**
     * @var int
     */
    protected $partition;

    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var array
     */
    protected $headers;

    public function __construct(Consumer $consumer, string $topic, int $partition, string $key, string $value, array $headers)
    {
        $this->consumer = $consumer;
        $this->topic = $topic;
        $this->partition = $partition;
        $this->key = $key;
        $this->value = $value;
        $this->headers = $headers;
    }

    public function getConsumer(): Consumer
    {
        return $this->consumer;
    }

    public function setConsumer(Consumer $consumer): self
    {
        $this->consumer = $consumer;

        return $this;
    }

    public function getTopic(): string
    {
        return $this->topic;
    }

    public function setTopic(string $topic): self
    {
        $this->topic = $topic;

        return $this;
    }

    public function getPartition(): int
    {
        return $this->partition;
    }

    public function setPartition(int $partition): self
    {
        $this->partition = $partition;

        return $this;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $key): self
    {
        $this->key = $key;

        return $this;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function setHeaders(array $headers): self
    {
        $this->headers = $headers;

        return $this;
    }
}
