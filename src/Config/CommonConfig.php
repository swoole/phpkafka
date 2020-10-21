<?php

declare(strict_types=1);

namespace longlang\phpkafka\Config;

class CommonConfig extends AbstractConfig
{
    /**
     * @var float
     */
    protected $connectTimeout = -1;

    /**
     * @var float
     */
    protected $sendTimeout = -1;

    /**
     * @var float
     */
    protected $recvTimeout = -1;

    /**
     * The client ID string.
     *
     * @var string
     */
    protected $clientId;

    /**
     * @var int
     */
    protected $maxWriteAttempts = 3;

    public function getConnectTimeout(): float
    {
        return $this->connectTimeout;
    }

    public function setConnectTimeout(float $connectTimeout): self
    {
        $this->connectTimeout = $connectTimeout;

        return $this;
    }

    public function setSendTimeout(float $value): self
    {
        $this->sendTimeout = $value;

        return $this;
    }

    public function getSendTimeout(): float
    {
        return $this->sendTimeout;
    }

    public function getRecvTimeout(): float
    {
        return $this->recvTimeout;
    }

    public function setRecvTimeout(float $recvTimeout): self
    {
        $this->recvTimeout = $recvTimeout;

        return $this;
    }

    public function getClientId(): ?string
    {
        return $this->clientId;
    }

    public function setClientId(?string $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }

    public function getMaxWriteAttempts(): int
    {
        return $this->maxWriteAttempts;
    }

    public function setMaxWriteAttempts(float $maxWriteAttempts): self
    {
        $this->maxWriteAttempts = $maxWriteAttempts;

        return $this;
    }
}
