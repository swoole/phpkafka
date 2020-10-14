<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol;

class AbstractRequestHeader extends AbstractStruct
{
    public static function parseVersion(int $requestApiVersion, array $flexibleVersions): int
    {
        return \in_array($requestApiVersion, $flexibleVersions) ? 2 : 1;
    }
}
