<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol;

class ApiKeys
{
    public const PROTOCOL_API_VERSIONS = 18;

    public const PROTOCOL_CREATE_TOPICS = 19;

    public const PROTOCOL_MAP = [
        self::PROTOCOL_API_VERSIONS  => 'ApiVersions',
        self::PROTOCOL_CREATE_TOPICS => 'CreateTopics',
    ];

    private function __construct()
    {
    }

    public static function createResponse(int $protocol, string $data = ''): AbstractResponse
    {
        $name = self::PROTOCOL_MAP[$protocol] ?? null;
        if (!$name) {
            throw new \RuntimeException(sprintf('Could not found api keys %d', $protocol));
        }
        $class = 'Longyan\Kafka\Protocol\ApiVersions\\' . $name . 'Response';
        /** @var AbstractResponse $instance */
        $instance = new $class();
        if ($data) {
            $instance->unpack($data);
        }

        return $instance;
    }
}
