<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\ResponseHeader;

use longlang\phpkafka\Protocol\AbstractResponseHeader;
use longlang\phpkafka\Protocol\ProtocolField;

class ResponseHeader extends AbstractResponseHeader
{
    /**
     * The correlation ID of this response.
     *
     * @var int
     */
    protected $correlationId = 0;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('correlationId', 'int32', false, [0, 1], [1], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [1];
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
}
