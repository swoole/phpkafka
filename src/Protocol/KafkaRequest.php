<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol;

use longlang\phpkafka\Protocol\RequestHeader\RequestHeader;
use longlang\phpkafka\Protocol\Type\Int32;

class KafkaRequest
{
    /**
     * @var int
     */
    protected $size;

    /**
     * @var RequestHeader
     */
    protected $header;

    /**
     * @var AbstractRequest
     */
    protected $request;

    /**
     * @var string
     */
    protected $data;

    public function __construct(AbstractRequest $request, RequestHeader $header)
    {
        $this->header = $header;
        $this->request = $request;
        $this->updateData();
    }

    public function pack(): string
    {
        return Int32::pack($this->size) . $this->data;
    }

    private function updateData()
    {
        $apiVersion = $this->header->getRequestApiVersion();
        $headerVersion = RequestHeader::parseVersion($apiVersion, $this->request->getFlexibleVersions());
        $this->data = $this->header->pack($headerVersion) . $this->request->pack($apiVersion);
        $this->size = \strlen($this->data);
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getHeader(): RequestHeader
    {
        return $this->header;
    }

    public function setHeader(RequestHeader $header): self
    {
        $this->header = $header;
        $this->updateData();

        return $this;
    }

    public function getRequest(): AbstractRequest
    {
        return $this->request;
    }

    public function setRequest(AbstractRequest $request): self
    {
        $this->request = $request;
        $this->updateData();

        return $this;
    }
}
