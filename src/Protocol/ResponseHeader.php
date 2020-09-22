<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol;

class ResponseHeader extends AbstractStruct
{
    /**
     * The correlation ID of this request.
     *
     * @var int
     */
    protected $correlationId;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('correlationId', 'Int32', null, 0),
            ];
            self::$taggedFieldses[self::class] = [];
        }
    }

    public function getFlexibleVersions(): ?int
    {
        return 1;
    }

    public function getCorrelationId(): int
    {
        return $this->correlationId;
    }

    public function setCorrelationId(int $correlationId): self
    {
        $this->correlationId = $correlationId;

        return $this;
    }

    public static function parseVersion(int $requestApiVersion, int $flexibleVersion): int
    {
        return $requestApiVersion >= $flexibleVersion ? 1 : 0;
    }
}
