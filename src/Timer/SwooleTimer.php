<?php

declare(strict_types=1);

namespace longlang\phpkafka\Timer;

use Swoole\Timer;

class SwooleTimer implements TimerInterface
{
    public function tick(int $interval, callable $callback, ...$params): int
    {
        return Timer::tick($interval, $callback, ...$params);
    }

    public function clear(int $timerId): void
    {
        Timer::clear($timerId);
    }
}
