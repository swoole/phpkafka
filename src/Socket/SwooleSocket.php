<?php

declare(strict_types=1);

namespace longlang\phpkafka\Socket;

use longlang\phpkafka\Config\CommonConfig;
use longlang\phpkafka\Exception\ConnectionException;
use longlang\phpkafka\Exception\SocketException;
use Swoole\Coroutine\Client;

class SwooleSocket implements SocketInterface
{
    /**
     * read socket max length 5MB.
     *
     * @var int
     */
    public const READ_MAX_LENGTH = 5242880;

    /**
     * max write socket buffer.
     *
     * @var int
     */
    public const MAX_WRITE_BUFFER = 2048;

    /**
     * @var string
     */
    protected $host;

    /**
     * @var int
     */
    protected $port;

    /**
     * @var \longlang\phpkafka\Config\CommonConfig|null
     */
    protected $config;

    /**
     * @var \Swoole\Coroutine\Client
     */
    protected $socket;

    /**
     * @var string
     */
    protected $receivedBuffer = '';

    public function __construct(string $host, int $port, ?CommonConfig $config = null)
    {
        $this->host = $host;
        $this->port = $port;
        if (null === $config) {
            $config = new CommonConfig();
        }
        $this->config = $config;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function getConfig(): CommonConfig
    {
        return $this->config;
    }

    public function isConnected(): bool
    {
        return null !== $this->socket;
    }

    public function connect(): void
    {
        $config = $this->config;
        $client = new Client(\SWOOLE_SOCK_TCP);
        $client->set([
            'connect_timeout' => $config->getConnectTimeout(),
            'read_timeout'    => $config->getRecvTimeout(),
            'write_timeout'   => $config->getSendTimeout(),
        ]);
        if ($client->connect($this->host, $this->port)) {
            $this->socket = $client;
        } else {
            throw new ConnectionException(sprintf('Could not connect to tcp://%s:%s (%s [%d])', $this->host, $this->port, $client->errMsg, $client->errCode));
        }
    }

    public function close(): bool
    {
        if ($this->socket) {
            $this->socket->close();
            $this->socket = null;
            $this->receivedBuffer = '';

            return true;
        } else {
            return false;
        }
    }

    public function send(string $data, ?float $timeout = null): int
    {
        $result = $this->socket->send($data);
        if (false === $result) {
            throw new SocketException(sprintf('Could not write data to stream, %s [%d]', $this->socket->errMsg, $this->socket->errCode));
        }

        return $result;
    }

    public function recv(int $length, ?float $timeout = null): string
    {
        $beginTime = microtime(true);
        if (null === $timeout) {
            $timeout = $this->config->getRecvTimeout();
        }
        $leftTime = $timeout;
        while ($this->socket && !isset($this->receivedBuffer[$length - 1]) && (-1 == $timeout || $leftTime > 0)) {
            $buffer = $this->socket->recv($timeout);
            if (false === $buffer) {
                throw new SocketException(sprintf('Could not recv data from stream, %s [%d]', $this->socket->errMsg, $this->socket->errCode));
            }
            $this->receivedBuffer .= $buffer;
            if ($timeout > 0) {
                $leftTime = $timeout - (microtime(true) - $beginTime);
            }
        }

        if (isset($this->receivedBuffer[$length - 1])) {
            $result = substr($this->receivedBuffer, 0, $length);
            $this->receivedBuffer = substr($this->receivedBuffer, $length);

            return $result;
        }

        if ($this->socket) {
            throw new SocketException('Could not recv data from stream');
        }

        return '';
    }
}
