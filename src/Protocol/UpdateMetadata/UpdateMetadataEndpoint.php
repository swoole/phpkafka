<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\UpdateMetadata;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolField;

class UpdateMetadataEndpoint extends AbstractStruct
{
    /**
     * The port of this endpoint.
     *
     * @var int
     */
    protected $port = 0;

    /**
     * The hostname of this endpoint.
     *
     * @var string
     */
    protected $host = '';

    /**
     * The listener name.
     *
     * @var string
     */
    protected $listener = '';

    /**
     * The security protocol type.
     *
     * @var int
     */
    protected $securityProtocol = 0;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('port', 'int32', false, [1, 2, 3, 4, 5, 6], [6], [], [], null),
                new ProtocolField('host', 'string', false, [1, 2, 3, 4, 5, 6], [6], [], [], null),
                new ProtocolField('listener', 'string', false, [3, 4, 5, 6], [6], [], [], null),
                new ProtocolField('securityProtocol', 'int16', false, [1, 2, 3, 4, 5, 6], [6], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [6];
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

    public function getHost(): string
    {
        return $this->host;
    }

    public function setHost(string $host): self
    {
        $this->host = $host;

        return $this;
    }

    public function getListener(): string
    {
        return $this->listener;
    }

    public function setListener(string $listener): self
    {
        $this->listener = $listener;

        return $this;
    }

    public function getSecurityProtocol(): int
    {
        return $this->securityProtocol;
    }

    public function setSecurityProtocol(int $securityProtocol): self
    {
        $this->securityProtocol = $securityProtocol;

        return $this;
    }
}
