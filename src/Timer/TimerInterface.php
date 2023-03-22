<?php

declare(strict_types=1);

namespace longlang\phpkafka\Timer;

interface TimerInterface
{
    public function tick(float $interval, callable $callback, ...$params): int;

    public function clear(int $timerId): void;
}
