<?php

declare(strict_types=1);

namespace longlang\phpkafka\Socket;

use longlang\phpkafka\Config\CommonConfig;
use longlang\phpkafka\Exception\ConnectionException;
use longlang\phpkafka\Exception\SocketException;

class StreamSocket implements SocketInterface
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
     * @var resource|null
     */
    protected $socket;

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
        $uri = $this->getURI();
        $socket = stream_socket_client(
            $uri,
            $errno,
            $errstr,
            $this->config->getConnectTimeout(),
            \STREAM_CLIENT_CONNECT,
            $this->getContext()
        );

        if (!\is_resource($socket)) {
            throw new ConnectionException(sprintf('Could not connect to %s (%s [%d])', $uri, $errstr, $errno));
        }
        $this->socket = $socket;
    }

    public function close(): bool
    {
        if (\is_resource($this->socket)) {
            fclose($this->socket);
            $this->socket = null;

            return true;
        } else {
            return false;
        }
    }

    public function send(string $data, ?float $timeout = null): int
    {
        // fwrite to a socket may be partial, so loop until we
        // are done with the entire buffer
        $failedAttempts = 0;
        $bytesWritten = 0;

        $bytesToWrite = \strlen($data);

        if (null === $timeout) {
            $timeout = $this->config->getSendTimeout();
        }
        while ($bytesWritten < $bytesToWrite) {
            // wait for stream to become available for writing
            $writable = $this->select([$this->socket], $timeout, false);

            if (false === $writable) {
                $this->close();
                throw new SocketException('Could not write ' . $bytesToWrite . ' bytes to stream');
            }

            if (0 === $writable) {
                $res = $this->getMetaData();
                $this->close();
                if (!empty($res['timed_out'])) {
                    throw new SocketException('Timed out writing ' . $bytesToWrite . ' bytes to stream after writing ' . $bytesWritten . ' bytes');
                }

                throw new SocketException('Could not write ' . $bytesToWrite . ' bytes to stream');
            }

            if ($bytesToWrite - $bytesWritten > self::MAX_WRITE_BUFFER) {
                // write max buffer size
                $wrote = fwrite($this->socket, substr($data, $bytesWritten, self::MAX_WRITE_BUFFER));
            } else {
                // write remaining buffer bytes to stream
                $wrote = fwrite($this->socket, substr($data, $bytesWritten));
            }

            if (-1 === $wrote || false === $wrote) {
                $this->close();
                throw new SocketException('Could not write ' . \strlen($data) . ' bytes to stream, completed writing only ' . $bytesWritten . ' bytes');
            }

            if (0 === $wrote) {
                // Increment the number of times we have failed
                ++$failedAttempts;

                if ($failedAttempts > $this->config->getMaxWriteAttempts()) {
                    $this->close();
                    throw new SocketException('After ' . $failedAttempts . ' attempts could not write ' . \strlen($data) . ' bytes to stream, completed writing only ' . $bytesWritten . ' bytes');
                }
            } else {
                // If we wrote something, reset our failed attempt counter
                $failedAttempts = 0;
            }

            $bytesWritten += $wrote;
        }

        return $bytesWritten;
    }

    public function recv(int $length, ?float $timeout = null): string
    {
        if ($length > self::READ_MAX_LENGTH) {
            throw new SocketException(sprintf('Invalid length %d given, it should be lesser than or equals to %d', $length, self::READ_MAX_LENGTH));
        }

        if (null === $timeout) {
            $timeout = $this->config->getRecvTimeout();
        }
        $readable = $this->select([$this->socket], $timeout);

        if (false === $readable) {
            $this->close();
            throw new SocketException(sprintf('Could not read %d bytes from stream (not readable)', $length));
        }

        if (0 === $readable) { // select timeout
            $res = $this->getMetaData();
            $this->close();

            if (!empty($res['timed_out'])) {
                throw new SocketException(sprintf('Timed out reading %d bytes from stream', $length));
            }

            throw new SocketException(sprintf('Could not read %d bytes from stream (not readable)', $length));
        }

        $remainingBytes = $length;
        $data = $chunk = '';

        while ($remainingBytes > 0) {
            $chunk = fread($this->socket, $remainingBytes);

            if (false === $chunk || 0 === \strlen($chunk)) {
                // Zero bytes because of EOF?
                if (feof($this->socket)) {
                    $this->close();
                    throw new SocketException(sprintf('Unexpected EOF while reading %d bytes from stream (no data)', $length));
                }
                // Otherwise wait for bytes
                $readable = $this->select([$this->socket], $timeout);
                if (1 !== $readable) {
                    $this->close();
                    throw new SocketException(sprintf('Timed out while reading %d bytes from stream, %d bytes are still needed', $length, $remainingBytes));
                }

                continue; // attempt another read
            }

            $data .= $chunk;
            $remainingBytes -= \strlen($chunk);
        }

        return $data;
    }

    /**
     * @return int|false
     */
    protected function select(array $sockets, float $timeout, bool $isRead = true)
    {
        $null = [];
        $timeoutSec = (int) $timeout;
        if ($timeoutSec < 0) {
            $timeoutSec = null;
        }
        $timeoutUsec = max((int) (1000000 * ($timeout - $timeoutSec)), 0);

        if ($isRead) {
            return stream_select($sockets, $null, $null, $timeoutSec, $timeoutUsec);
        }

        return stream_select($null, $sockets, $null, $timeoutSec, $timeoutUsec);
    }

    protected function getMetaData(): array
    {
        return stream_get_meta_data($this->socket);
    }

    /**
     * @return resource
     */
    protected function getContext()
    {
        return stream_context_create([
            'ssl' => $this->config->getSsl()->getStreamConfig($this->getHost()),
        ]);
    }

    protected function getURI(): string
    {
        $protocol = 'tcp';
        $ssl = $this->getConfig()->getSsl();
        if ($ssl->getOpen()) {
            $protocol = 'ssl';
        }

        return sprintf('%s://%s:%s', $protocol, $this->host, $this->port);
    }
}
