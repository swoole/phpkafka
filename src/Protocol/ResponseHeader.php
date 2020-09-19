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
        $this->map = [
            'correlationId'     => new ProtocolField('Int32', null, 0),
        ];
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
