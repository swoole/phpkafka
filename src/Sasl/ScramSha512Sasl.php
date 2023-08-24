<?php

declare(strict_types=1);

namespace longlang\phpkafka\Sasl;

use longlang\phpkafka\Config\CommonConfig;
use longlang\phpkafka\Protocol\ErrorCode;
use longlang\phpkafka\Sasl\SaslInterface;

class ScramSha512Sasl implements SaslInterface
{
    /**
     * @var CommonConfig
     */
    protected $config;

    protected string $nonce = '';
    protected string $saltedPassword = '';
    protected string $authMessage = '';

    public function __construct(CommonConfig $config)
    {
        $this->config = $config;
        $this->nonce = base64_encode(random_bytes(16));
    }

    /**
     * 授权模式.
     */
    public function getName(): string
    {
        return 'SCRAM-SHA-512';
    }

    /**
     * SCRAM-SHA-512 第一次握手信息
     *
     * @return string
     */
    public function getAuthBytes(): string
    {
        $config = $this->config->getSasl();
        if (empty($config['username']) || empty($config['password'])) {
            throw new KafkaErrorException('sasl not found auth info');
        }

        return sprintf('n,,%s', $this->getFirstMessageBare());
    }

    /**
     * 获取第一次握手信息
     *
     * @return string
     */
    private function getFirstMessageBare(): string
    {
        return sprintf('n=%s,r=%s', $this->getSaslConfig('username'), $this->nonce);
    }

    /**
     * 获取 SASL 所有配置
     *
     * @return array
     */
    public function getSaslConfigs(): array
    {
        return $this->config->getSasl();
    }

    /**
     * 获取 SASL 配置
     *
     * @param string $key
     * @return mixed
     */
    public function getSaslConfig(string $key): mixed
    {
        return $this->getSaslConfigs()[$key] ?? null;
    }

    /**
     * 获取 SASL 密码
     *
     * @return string
     */
    private function getPassword(): string
    {
        return $this->getSaslConfig('password') ?: '';
    }

    /**
     * 计算第二次握手信息
     *
     * @param string $response
     * @return string
     */
    public function getFinalMessage(string $response): string
    {
        // 拆分第一次握手后的响应
        [$r, $s, $i] = explode(',', $response);

        // 提取随机数、盐和迭代次数
        $serverNonce = $this->ltrimMessage($r);
        $salt = base64_decode($this->ltrimMessage($s));
        $iterations = (int) $this->ltrimMessage($i);

        // 计算第二次握手的参数
        $this->saltedPassword = $saltedPassword = $this->calculateSaltedPassword($this->getPassword(), $salt, $iterations);

        $clientKey = $this->calculateClientKey($saltedPassword);
        $storedKey = $this->calculateStoredKey($clientKey);

        $clientFirstMessageBare = $this->getFirstMessageBare();
        $serverFirstMessage = $response;
        $clientFinalMessageWithoutProof = $this->getMessageWithoutProof($serverNonce);

        $this->authMessage = $authMessage = sprintf('%s,%s,%s', $clientFirstMessageBare, $serverFirstMessage, $clientFinalMessageWithoutProof);
        $clientSignature = $this->hmac($authMessage, $storedKey);

        return sprintf('%s,p=%s', $clientFinalMessageWithoutProof, base64_encode($clientKey ^ $clientSignature));
    }

    /**
     * 计算盐化密码
     * 使用 PBKDF2 函数和服务器提供的盐和迭代次数来计算盐化密码
     *
     * @param string $password
     * @param string $salt
     * @param integer $iterations
     * @return string
     */
    private function calculateSaltedPassword(string $password, string $salt, int $iterations): string
    {
        return hash_pbkdf2('sha512', $password, $salt, $iterations, 0, true);
    }

    /**
     * 计算客户端密钥
     * 使用盐化密码和 HMAC 函数来计算客户端密钥
     *
     * @param string $saltedPassword
     * @return string
     */
    private function calculateClientKey(string $saltedPassword): string
    {
        // 在 SCRAM-SHA-512 中需要用盐化密码来加密计算密，密钥钥固定是 Client Key
        return $this->hmac('Client Key', $saltedPassword);
    }

    /**
     * 计算存储密钥
     * 使用客户端密钥和 SHA-256 函数来计算存储密钥
     *
     * @param string $clientKey
     * @return string
     */
    private function calculateStoredKey(string $clientKey): string
    {
        return hash('sha512', $clientKey, true);
    }

    /**
     * 获取不带证明的消息
     *
     * @param string $nonce
     * @return string
     */
    private function getMessageWithoutProof(string $nonce): string
    {
        return sprintf('c=biws,r=%s', $nonce);
    }

    /**
     * sha512 加密
     *
     * @param string $data
     * @param string $key
     * @return string
     */
    public function hmac(string $data, string $key): string
    {
        return hash_hmac('sha512', $data, $key, true);
    }

    /**
     * 删除服务响应信息的前两个字符
     *
     * @param string $param
     * @return string
     */
    public function ltrimMessage(string $param): string
    {
        return substr($param, 2);
    }

    /**
     * 是否启用最终签名验证
     *
     * @return boolean
     */
    public function enableFinalSignatureVerification(): bool
    {
        return (bool) $this->getSaslConfig('verify_final_signature');
    }

    /**
     * 验证最终签名
     *
     * @param string $message
     * @return void
     */
    public function verifyFinalMessage(string $message): void
    {
        $receivedSignature = $this->ltrimMessage($message);
        $receivedSignature = base64_decode($receivedSignature);

        $serverKey = $this->hmac('Server Key', $this->saltedPassword);
        $expectedSignature = $this->hmac($this->authMessage, $serverKey);
        
        if (! hash_equals($receivedSignature, $expectedSignature)) {
            ErrorCode::check(ErrorCode::SASL_AUTHENTICATION_FAILED);
        }
    }
}
