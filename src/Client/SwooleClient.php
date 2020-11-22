<?php

declare(strict_types=1);

namespace longlang\phpkafka\Client;

use InvalidArgumentException;
use longlang\phpkafka\Config\CommonConfig;
use longlang\phpkafka\Protocol\AbstractRequest;
use longlang\phpkafka\Protocol\AbstractResponse;
use longlang\phpkafka\Protocol\ApiKeys;
use longlang\phpkafka\Protocol\KafkaRequest;
use longlang\phpkafka\Protocol\RequestHeader\RequestHeader;
use longlang\phpkafka\Protocol\ResponseHeader\ResponseHeader;
use longlang\phpkafka\Protocol\Type\Int32;
use longlang\phpkafka\Socket\SwooleSocket;
use RuntimeException;
use Swoole\Coroutine;
use Swoole\Coroutine\Channel;

class SwooleClient extends SyncClient
{
    /**
     * @var bool
     */
    protected $coRecvRunning = false;

    /**
     * @return \Swoole\Coroutine\Channel[]
     */
    protected $recvChannels = [];

    /**
     * @var int|bool
     */
    private $recvCoId = false;

    public function __construct(string $host, int $port, ?CommonConfig $config = null, string $socketClass = SwooleSocket::class)
    {
        parent::__construct($host, $port, $config, $socketClass);
    }

    public function connect(): void
    {
        parent::connect();
        $this->connected = true;
    }

    public function close(): bool
    {
        if ($this->socket->close()) {
            $this->connected = false;
            $this->recvChannels = [];

            return true;
        } else {
            return false;
        }
    }

    /**
     * Send message to kafka server.
     *
     * If successful, return the correlationId
     */
    public function send(AbstractRequest $request, ?RequestHeader $header = null, bool $hasResponse = true): int
    {
        $apiKey = $request->getRequestApiKey();
        if (null === $header) {
            $header = new RequestHeader();
            $header->setRequestApiKey($apiKey);
            $header->setRequestApiVersion($this->getRequestApiVersion($request));
            $header->setClientId($this->getConfig()->getClientId());
            $header->setCorrelationId(++$this->correlationIdIncrValue);
        }
        $kafkaRequest = new KafkaRequest($request, $header);
        $correlationId = $header->getCorrelationId();
        $this->recvChannels[$correlationId] = new Channel(1);
        if ($hasResponse) {
            $this->waitResponseMaps[$correlationId] = [
                'apiKey'           => $apiKey,
                'apiVersion'       => $header->getRequestApiVersion(),
                'flexibleVersions' => $request->getFlexibleVersions(),
            ];
        }
        $this->socket->send($kafkaRequest->pack());

        return $correlationId;
    }

    public function recv(?int $correlationId, ?ResponseHeader &$header = null): AbstractResponse
    {
        if (!isset($this->waitResponseMaps[$correlationId])) {
            throw new InvalidArgumentException(sprintf('Invalid correlationId %s', $correlationId));
        }
        $mapData = $this->waitResponseMaps[$correlationId];
        $recvCoId = $this->recvCoId;
        if (!$recvCoId || (true !== $recvCoId && !Coroutine::exists($recvCoId))) {
            $this->startRecvCo();
        }
        if (isset($this->recvChannels[$correlationId])) {
            $channel = $this->recvChannels[$correlationId];
        } else {
            $this->recvChannels[$correlationId] = $channel = new Channel(1);
        }
        $data = $channel->pop($this->getConfig()->getRecvTimeout());
        unset($this->recvChannels[$correlationId]);
        if (false === $data) {
            throw new RuntimeException('Recv data failed');
        }

        $header = new ResponseHeader();
        $header->unpack($data, $size, ResponseHeader::parseVersion($mapData['apiVersion'], $mapData['flexibleVersions']));
        $data = substr($data, $size);

        $result = ApiKeys::createResponse($mapData['apiKey'], $data, $mapData['apiVersion']);
        unset($this->waitResponseMaps[$correlationId]);

        return $result;
    }

    private function startRecvCo()
    {
        $this->coRecvRunning = true;
        $this->recvCoId = true;
        $this->recvCoId = Coroutine::create(function () {
            while ($this->coRecvRunning) {
                $data = $this->socket->recv(4, -1);
                if ('' === $data) {
                    break;
                }
                $length = Int32::unpack($data);
                $data = $this->socket->recv($length);
                $correlationId = Int32::unpack($data);
                if (!isset($this->recvChannels[$correlationId])) {
                    continue;
                }
                $this->recvChannels[$correlationId]->push($data);
            }
        });
    }
}
