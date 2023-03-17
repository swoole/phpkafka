<?php

namespace longlang\phpkafka\Timer;

class SwowTimer implements TimerInterface
{
    
    public function tick(float $interval, callable $callback, ...$params): int
    {
        // todo
        return 0;
    }

    public function clear(int $timerId): void
    {
        // todo
    }

}
