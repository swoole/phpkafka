<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol;

class ApiKeys extends AbstractApiKeys
{
    public static function createResponse(int $protocol, string $data = '', int $apiVersion = 0): AbstractResponse
    {
        $name = self::PROTOCOL_MAP[$protocol] ?? null;
        if (!$name) {
            throw new \RuntimeException(sprintf('Could not found api keys %d', $protocol));
        }
        $class = 'longlang\phpkafka\Protocol\\' . $name . '\\' . $name . 'Response';
        /** @var AbstractResponse $instance */
        $instance = new $class();
        if ($data) {
            $instance->unpack($data, $size, $apiVersion);
        }

        return $instance;
    }
}
