<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\WriteTxnMarkers;

use longlang\phpkafka\Protocol\AbstractRequest;
use longlang\phpkafka\Protocol\ProtocolField;

class WriteTxnMarkersRequest extends AbstractRequest
{
    /**
     * The transaction markers to be written.
     *
     * @var WritableTxnMarker[]
     */
    protected $markers = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('markers', WritableTxnMarker::class, true, [0], [], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 27;
    }

    public function getMaxSupportedVersion(): int
    {
        return 0;
    }

    public function getFlexibleVersions(): array
    {
        return [];
    }

    /**
     * @return WritableTxnMarker[]
     */
    public function getMarkers(): array
    {
        return $this->markers;
    }

    /**
     * @param WritableTxnMarker[] $markers
     */
    public function setMarkers(array $markers): self
    {
        $this->markers = $markers;

        return $this;
    }
}
