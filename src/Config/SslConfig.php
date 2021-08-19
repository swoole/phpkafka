<?php

declare(strict_types=1);

namespace longlang\phpkafka\Config;

class SslConfig extends AbstractConfig
{
    /**
     * @var bool
     */
    protected $open = false;

    /**
     * @var bool
     */
    protected $compression = true;

    /**
     * @var string
     */
    protected $certFile = '';

    /**
     * @var string
     */
    protected $keyFile = '';

    /**
     * @var string
     */
    protected $passphrase = '';

    /**
     * @var string
     */
    protected $peerName = '';

    /**
     * @var bool
     */
    protected $verifyPeer = false;

    /**
     * @var bool
     */
    protected $verifyPeerName = false;

    /**
     * @var bool
     */
    protected $allowSelfSigned = false;

    /**
     * @var string
     */
    protected $cafile = '';

    /**
     * @var string
     */
    protected $capath = '';

    /**
     * @var int
     */
    protected $verifyDepth = 0;

    public function getOpen(): bool
    {
        return $this->open;
    }

    public function setOpen(bool $open): self
    {
        $this->open = $open;

        return $this;
    }

    public function getCompression(): bool
    {
        return $this->compression;
    }

    public function setCompression(bool $compression): self
    {
        $this->compression = $compression;

        return $this;
    }

    public function getCertFile(): string
    {
        return $this->certFile;
    }

    public function setCertFile(string $certFile): void
    {
        $this->certFile = $certFile;
    }

    public function getKeyFile(): string
    {
        return $this->keyFile;
    }

    public function setKeyFile(string $keyFile): void
    {
        $this->keyFile = $keyFile;
    }

    public function getPassphrase(): string
    {
        return $this->passphrase;
    }

    public function setPassphrase(string $passphrase): self
    {
        $this->passphrase = $passphrase;

        return $this;
    }

    public function getPeerName(): string
    {
        return $this->peerName;
    }

    public function setPeerName(string $peerName): self
    {
        $this->peerName = $peerName;

        return $this;
    }

    public function getVerifyPeer(): bool
    {
        return $this->verifyPeer;
    }

    public function setVerifyPeer(bool $verifyPeer): void
    {
        $this->verifyPeer = $verifyPeer;
    }

    public function getVerifyPeerName(): bool
    {
        return $this->verifyPeerName;
    }

    public function setVerifyPeerName(bool $verifyPeerName): self
    {
        $this->verifyPeerName = $verifyPeerName;

        return $this;
    }

    public function getAllowSelfSigned(): bool
    {
        return $this->allowSelfSigned;
    }

    public function setAllowSelfSigned(bool $allowSelfSigned): void
    {
        $this->allowSelfSigned = $allowSelfSigned;
    }

    public function getCafile(): string
    {
        return $this->cafile;
    }

    public function setCafile(string $cafile): self
    {
        $this->cafile = $cafile;

        return $this;
    }

    public function getCapath(): string
    {
        return $this->capath;
    }

    public function setCapath(string $capath): self
    {
        $this->capath = $capath;

        return $this;
    }

    public function getVerifyDepth(): int
    {
        return $this->verifyDepth;
    }

    public function setVerifyDepth(int $verifyDepth): void
    {
        $this->verifyDepth = $verifyDepth;
    }

    public function getSwooleConfig(string $host): array
    {
        if (!$this->getOpen()) {
            return [];
        }
        $config['ssl_compress'] = $this->getCompression();
        '' != $this->getCertFile() && $config['ssl_cert_file'] = $this->getCertFile();
        '' != $this->getKeyFile() && $config['ssl_key_file'] = $this->getKeyFile();
        '' != $this->getPassphrase() && $config['ssl_passphrase'] = $this->getPassphrase();
        '' != $this->getCafile() && $config['ssl_cafile'] = $this->getCafile();
        '' != $this->getCapath() && $config['ssl_capath'] = $this->getCapath();
        $config['ssl_verify_peer'] = $this->getVerifyPeer();
        $config['ssl_allow_self_signed'] = $this->getAllowSelfSigned();
        $config['ssl_verify_depth'] = $this->getVerifyDepth();
        if ($this->getVerifyPeerName()) {
            $config['ssl_host_name'] = $this->getPeerName() ?: $host;
        }

        return $config;
    }

    public function getStreamConfig(string $host): array
    {
        if (!$this->getOpen()) {
            return [];
        }
        $config['disable_compression'] = !$this->getCompression();
        '' != $this->getCertFile() && $config['local_cert'] = $this->getCertFile();
        '' != $this->getKeyFile() && $config['local_pk'] = $this->getKeyFile();
        '' != $this->getPassphrase() && $config['passphrase'] = $this->getPassphrase();
        '' != $this->getCafile() && $config['cafile'] = $this->getCafile();
        '' != $this->getCapath() && $config['capath'] = $this->getCapath();
        $config['verify_peer'] = $this->getVerifyPeer();
        $config['allow_self_signed'] = $this->getAllowSelfSigned();
        if ($this->getVerifyDepth() > 0) {
            $config['verify_depth'] = $this->getVerifyDepth();
        }
        $config['verify_peer_name'] = $this->getVerifyPeerName();
        $config['peer_name'] = $this->getPeerName() ?: $host;

        return $config;
    }
}
