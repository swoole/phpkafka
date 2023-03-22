<?php

declare(strict_types=1);

namespace longlang\phpkafka\Timer;

interface TimerInterface
{
    public function tick(int $interval, callable $callback): int;

    public function clear(int $timerId): void;
}
