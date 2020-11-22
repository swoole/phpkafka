<?php

declare(strict_types=1);

namespace longlang\phpkafka\Group;

class CoordinatorType
{
    public const GROUP = 0;

    public const TRANSACTION = 1;

    private function __construct()
    {
    }
}
