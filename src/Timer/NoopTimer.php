<?php

namespace longlang\phpkafka\Timer;

class NoopTimer implements TimerInterface
{
    public function tick(float $interval, callable $callback, ...$params): int
    {
        return 0;
    }

    public function clear(int $timerId): void
    {
    }
}
