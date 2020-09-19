<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol;

abstract class AbstractRequest extends AbstractProtocol
{
    abstract public function getMaxSupportedVersion(): int;

    public function getApiKeyText(): string
    {
        return ApiKeys::PROTOCOL_MAP[$this->getRequestApiKey()] ?? 'Unknown';
    }
}
