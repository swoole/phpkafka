<?php

declare(strict_types=1);

namespace longlang\phpkafka\Sasl;

use longlang\phpkafka\Config\CommonConfig;
use longlang\phpkafka\Exception\KafkaErrorException;

class PlainSasl implements SaslInterface
{
    /**
     * @var CommonConfig
     */
    protected $config;

    public function __construct(CommonConfig $config)
    {
        $this->config = $config;
    }

    /**
     * 授权模式.
     */
    public function getName(): string
    {
        return 'PLAIN';
    }

    /**
     * 获得加密串.
     */
    public function getAuthBytes(): string
    {
        $config = $this->config->getSasl();
        if (empty($config['username']) || empty($config['password'])) {
            // 不存在就报错
            throw new KafkaErrorException('sasl not found auth info');
        }

        return sprintf("\x00%s\x00%s", $config['username'], $config['password']);
    }
}
