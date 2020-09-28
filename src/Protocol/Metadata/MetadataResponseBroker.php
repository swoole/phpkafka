<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\Metadata;

use Longyan\Kafka\Protocol\AbstractStruct;
use Longyan\Kafka\Protocol\ProtocolField;

class MetadataResponseBroker extends AbstractStruct
{
    /**
     * The broker ID.
     *
     * @var int
     */
    protected $brokerId;

    /**
     * The broker hostname.
     *
     * @var string
     */
    protected $host;

    /**
     * The broker port.
     *
     * @var int
     */
    protected $port;

    /**
     * The rack of the broker, or null if it has not been assigned to a rack.
     *
     * @var string|null
     */
    protected $rack = 'null';

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('brokerId', 'int32', false, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9], [9], [], [], null),
                new ProtocolField('host', 'string', false, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9], [9], [], [], null),
                new ProtocolField('port', 'int32', false, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9], [9], [], [], null),
                new ProtocolField('rack', 'string', false, [1, 2, 3, 4, 5, 6, 7, 8, 9], [9], [1, 2, 3, 4, 5, 6, 7, 8, 9], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [9];
    }

    public function getBrokerId(): int
    {
        return $this->brokerId;
    }

    public function setBrokerId(int $brokerId): self
    {
        $this->brokerId = $brokerId;

        return $this;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function setHost(string $host): self
    {
        $this->host = $host;

        return $this;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function setPort(int $port): self
    {
        $this->port = $port;

        return $this;
    }

    public function getRack(): ?string
    {
        return $this->rack;
    }

    public function setRack(?string $rack): self
    {
        $this->rack = $rack;

        return $this;
    }
}
