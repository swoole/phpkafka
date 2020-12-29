<?php

declare(strict_types=1);

namespace longlang\phpkafka\Consumer\Struct;

class ConsumerGenerationPair
{
    /**
     * @var string
     */
    private $consumer;

    /**
     * @var int
     */
    private $generation;

    public function __construct(string $consumer, int $generation)
    {
        $this->consumer = $consumer;
        $this->generation = $generation;
    }

    public function getConsumer()
    {
        return $this->consumer;
    }

    public function getGeneration()
    {
        return $this->generation;
    }
}
