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
    const SIGN_VERSION = "2020_10_22";
    const SIGN_SERVICE = "kafka-cluster";
    const SIGN_ACTION = "kafka-cluster:Connect";
    const SIGN_VERSION_KEY = "version";
    const SIGN_HOST_KEY = "host";
    const SIGN_USER_AGENT_KEY = "user-agent";
    const SIGN_ACTION_KEY = "action";
    const QUERY_ACTION_KEY = "Action";

    /**
     * @var CommonConfig
     */
    protected $config;

    protected $host;


    public function __construct(CommonConfig $config)
    {
        $this->config = $config;
    }

    public function setHost(string $host)
    {
        $this->host = $host;
    }

    /**
     * 授权模式.
     */
    public function getName(): string
    {
        return 'AWS_MSK_IAM';
    }

    /**
     * 获得加密串.
     */
    public function getAuthBytes(): string
    {
        $config = $this->config->getSasl();
        if (empty($config['host']) || empty($config['region'])) {
            // 不存在就报错
            throw new KafkaErrorException('sasl not found auth info');
        }
       
        $query = http_build_query(Array(
            self::QUERY_ACTION_KEY => self::SIGN_ACTION
        ));

        $host = $config['host'];

        $region = $config['region'];

        $url = "kafka://".$host."/".$query."/";

        $req = new Request('GET', $url);

        $expiry = "+5 minutes";

        $credentials = call_user_func(CredentialProvider::defaultProvider())->wait();

        $signer = new SignatureV4(self::SIGN_SERVICE, $region);
        $signedReq = $signer->presign($req, $credentials, $expiry);
        $signedUri =  $signedReq->getUri();
        $url_components = parse_url((string)$signedUri);
        parse_str($url_components['query'], $params);

        $signedMap = Array(
            self::SIGN_VERSION_KEY => self::SIGN_VERSION,
            self::SIGN_HOST_KEY => $host,
            self::SIGN_USER_AGENT_KEY => "php-kafka/sasl/aws_msk_iam",
            self::SIGN_ACTION_KEY => self::SIGN_ACTION
        );

        foreach (array_keys($params) as $key) {
            $signedMap[strtolower($key)] = $params[$key];
        }

        $signedMap[strtolower(self::QUERY_ACTION_KEY)] = self::SIGN_ACTION;

        return json_encode($signedMap);
    }
}
