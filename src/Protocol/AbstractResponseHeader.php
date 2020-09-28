<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol;

class AbstractResponseHeader extends AbstractStruct
{
    public static function parseVersion(int $requestApiVersion, array $flexibleVersions): int
    {
        return \in_array($requestApiVersion, $flexibleVersions) ? 1 : 0;
    }
}
