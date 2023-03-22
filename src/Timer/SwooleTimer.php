<?php

declare(strict_types=1);

namespace longlang\phpkafka\Timer;

use Swoole\Timer;

class SwooleTimer implements TimerInterface
{
    public function tick(int $interval, callable $callback): int
    {
        return Timer::tick($interval, $callback);
    }

    public function clear(int $timerId): void
    {
        Timer::clear($timerId);
    }
}
