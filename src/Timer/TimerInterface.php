<?php

namespace longlang\phpkafka\Timer;

interface TimerInterface
{
    public function tick(float $interval, callable $callback, ...$params): int;
    public function clear(int $timerId): void;
}
