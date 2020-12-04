<?php

declare(strict_types=1);

namespace longlang\phpkafka\Consumer\Assignor;

abstract class AbstractPartitionAssignor implements PartitionAssignorInterface
{
    /**
     * @param string[] $topics
     */
    public function subscriptionUserData(array $topics): string
    {
        return '';
    }
}
