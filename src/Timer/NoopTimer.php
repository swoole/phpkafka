<?php

declare(strict_types=1);

namespace longlang\phpkafka\Timer;

class NoopTimer implements TimerInterface
{
    public function tick(int $interval, callable $callback): int
    {
        return 0;
    }

    public function clear(int $timerId): void
    {
    }
}
