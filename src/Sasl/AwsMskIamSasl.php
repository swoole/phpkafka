<?php

declare(strict_types=1);

namespace longlang\phpkafka\Sasl;

use longlang\phpkafka\Config\CommonConfig;
use longlang\phpkafka\Exception\KafkaErrorException;
use Aws\Credentials\CredentialProvider;
use Aws\Signature\SignatureV4;
use GuzzleHttp\Psr7\Request;

class AwsMskIamSasl implements SaslInterface
{
    const SIGN_ACTION = "kafka-cluster:Connect";
    const SIGN_SERVICE = "kafka-cluster";
    const SIGN_VERSION = "2020_10_22";
    const SIGN_ACTION_KEY = "action";
    const SIGN_HOST_KEY = "host";
    const SIGN_USER_AGENT_KEY = "user-agent";
    const SIGN_VERSION_KEY = "version";
    const QUERY_ACTION_KEY = "Action";


    /**
     * @var CommonConfig
     */
    protected $config;

    /**
     * @var string
     */
    protected $host;


    public function __construct(CommonConfig $config)
    {
        $this->config = $config;
    }

    public function setHost(string $host): void
    {
        $this->host = $host;
    }

    /**
     * Authorization mode
     */
    public function getName(): string
    {
        return 'AWS_MSK_IAM';
    }

    /**
     * Generated the Signed JSON used by AWS_MSK_IAM as auth string
     * @throws KafkaErrorException
     */
    public function getAuthBytes(): string
    {
        $config = $this->config->getSasl();
        if (empty($this->host) || empty($config['region'])) {
            throw new KafkaErrorException('AWS MSK config params not found');
        }

        $query = http_build_query(array(
            self::QUERY_ACTION_KEY => self::SIGN_ACTION,
        ));

        if (empty($config['expiration'])) {
            $expiration = "+5 minutes";
        } else {
            $expiration = $config['expiration'];
        }

        $region = $config['region'];

        $url = "kafka://" . $this->host . "/?" . $query;
        $provider = CredentialProvider::defaultProvider();
        // Returns a CredentialsInterface or throws.
        $creds = $provider()->wait();

        $req = new Request('GET', $url);

        $signer = new SignatureV4(self::SIGN_SERVICE, $region);
        $signedReq = $signer->presign($req, $creds, $expiration);
        $signedUri = $signedReq->getUri();

        parse_str($signedUri->getQuery(), $params);

        $headers = $signedReq->getHeaders();

        $signedMap = array(
            self::SIGN_VERSION_KEY => self::SIGN_VERSION,
            self::SIGN_USER_AGENT_KEY => "php-kafka/sasl/aws_msk_iam/" . PHP_VERSION,
            self::SIGN_ACTION_KEY => self::SIGN_ACTION,
            self::SIGN_HOST_KEY => $this->host
        );

        foreach ($params as $params_key => $params_value) {
            $signedMap[strtolower($params_key)] = $params_value;
        }

        foreach ($headers as $header_key => $header_value) {
            if (strtolower($header_key) != strtolower(self::SIGN_HOST_KEY)) {
                $signedMap[strtolower($header_key)] = $header_value;
            }
        }
        return json_encode($signedMap);
    }
}
