<?php

declare(strict_types=1);

namespace longlang\phpkafka\Consumer\Assignor\Struct;

class TopicAndPartition
{
    /**
     * @var string
     */
    protected $topic;

    /**
     * @var int
     */
    protected $partition;

    public function __construct(string $topic, int $partition)
    {
        $this->topic = $topic;
        $this->partition = $partition;
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
}
