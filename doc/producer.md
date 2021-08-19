# 生产者

## 生产者配置

类名：`longlang\phpkafka\Producer\ProducerConfig`

> 支持构造方法传入数组赋值

### 配置参数

| 参数名 | 说明 | 默认值 |
| - | - | - |
| connectTimeout | 连接超时时间（单位：秒，支持小数），为`-1`则不限制 | `-1` |
| sendTimeout | 发送超时时间（单位：秒，支持小数），为`-1`则不限制 | `-1` |
| recvTimeout | 接收超时时间（单位：秒，支持小数），为`-1`则不限制 | `-1` |
| clientId | Kafka 客户端标识 | `null` |
| maxWriteAttempts | 最大写入尝试次数 | `3` |
| client | 使用哪个 Kafka 客户端类，默认为`null`时根据场景自动识别 | `null` |
| socket | 使用哪个 Kafka Socket 类，默认为`null`时根据场景自动识别 | `null` |
| brokers | 手动配置 brokers 列表，如果要使用手动配置，请把`updateBrokers`设为`true`。格式：`'127.0.0.1:9092,127.0.0.1:9093'` 或 `['127.0.0.1:9092','127.0.0.1:9093']` | `null` |
| bootstrapServers | 别名bootstrapServer，引导服务器，如果配置了该值，会自动连接该服务器，并自动更新 brokers。格式：`'127.0.0.1:9092,127.0.0.1:9093'` 或 `['127.0.0.1:9092','127.0.0.1:9093']` | `null` |
| updateBrokers | 是否自动更新 brokers | `true` |
| acks | 生产者要求领导者，在确认请求完成之前已收到的确认数值。允许的值：0表示无确认，1表示仅领导者，-1表示完整的ISR。 | `0` |
| producerId | 生产者 ID | `-1` |
| producerEpoch | 生产者 Epoch | `-1` |
| partitionLeaderEpoch | 分区 Leader Epoch | `-1` |
| autoCreateTopic | 自动创建主题 | `true` |
| exceptionCallback | 遇到无法在`recv()`协程抛出的异常时，调用此回调。格式：`function(\Exception $e){}` | `null` |
| partitioner | 分区策略 |  默认策略：`\longlang\phpkafka\Producer\Partitioner\DefaultPartitioner` |
| produceRetry | 生产消息，匹配预设的错误码时，自动重试次数 | `3` |
| produceRetrySleep | 生产消息重试延迟，单位：秒 | `0.1` |
| sasl | SASL身份认证信息。为空则不发送身份认证信息 [详情](#SASL支持) | `[]`|
| ssl | SSL链接相关信息,为空则不使用SSL [详情](#SSL支持) | `null` |

**默认分区策略：**

如果指定了分区，则使用指定的分区；

如果没有指定分区，但指定了 `key`，会根据 `key` 的哈希值（crc32）选择分区；

如果没有指定分区，也没有指定 `key`，会使用轮询策略。

## 发送单个消息

**代码示例：**

```php
use longlang\phpkafka\Producer\Producer;
use longlang\phpkafka\Producer\ProducerConfig;

$config = new ProducerConfig();
$config->setBootstrapServer('127.0.0.1:9092');
$config->setUpdateBrokers(true);
$config->setAcks(-1);
$producer = new Producer($config);
$topic = 'test';
$value = (string) microtime(true);
$key = uniqid('', true);
$producer->send('test', $value, $key);
```

## 批量发送消息

**代码示例：**

```php
use longlang\phpkafka\Producer\ProduceMessage;
use longlang\phpkafka\Producer\Producer;
use longlang\phpkafka\Producer\ProducerConfig;

$config = new ProducerConfig();
$config->setBootstrapServer('127.0.0.1:9092');
$config->setUpdateBrokers(true);
$config->setAcks(-1);
$producer = new Producer($config);
$topic = 'test';
$partition0 = 0;
$partition1 = 1;
$producer->sendBatch([
    new ProduceMessage($topic, 'v1', 'k1', $partition0),
    new ProduceMessage($topic, 'v2', 'k2', $partition1),
]);
```



## SASL支持
### 相关配置
|参数名|说明|默认值|
| - | - | - |
| type | SASL授权对应的类。PLAIN为``\longlang\phpkafka\Sasl\PlainSasl::class``| ''|
| username | 账号 | '' |
| password | 密码 | '' |

**代码示例：**
```php
use longlang\phpkafka\Producer\ProducerConfig;

$config = new ProducerConfig();
// .... 你的其他配置
$config->setSasl([
    "type"=>\longlang\phpkafka\Sasl\PlainSasl::class,
    "username"=>"admin",
    "password"=>"admin-secret"
]);
$producer = new Producer($config);
// ....  你的业务代码
```


## SSL支持
类名：`longlang\phpkafka\Config\SslConfig`

> 支持构造方法传入数组赋值
### 配置参数

|参数名|说明|默认值|
| - | - | - |
| open  | 是否开启SSL传输加密 | `false` |
| compression | 是否开启压缩 | `true`  |
| certFile |cert证书存放路径|`''`|
| keyFile |私钥存放路径|`''`|
| passphrase |  cert证书密码 | `''`|
| peerName| 服务器主机名。默认为链接的host| `''`|
| verifyPeer |是否校验远端证书 | `false` |
| verifyPeerName |是否校验远端服务器名称 | `false` |
| verifyDepth | 如果证书链条层次太深，超过了本选项的设定值，则终止验证。 默认不校验层级 | `0`|
| allowSelfSigned | 是否允许自签证书 | `false` | 
| cafile | CA证书路径 | `''`|
| capath  | CA证书目录。会自动扫描该路径下所有pem文件 | `''`|

**代码示例：**

```php
use longlang\phpkafka\Producer\ProducerConfig;
use longlang\phpkafka\Producer\Producer;
use longlang\phpkafka\Config\SslConfig;

$config = new ProducerConfig();
// .... 你的其他配置
$sslConfig = new SslConfig();
$sslConfig->setOpen(true);
$sslConfig->setVerifyPeer(true);
$sslConfig->setAllowSelfSigned(true);
$sslConfig->setCafile("/kafka-client/.github/kafka/cert/ca-cert");
$config->setSsl($sslConfig);
$producer = new Producer($config);
// ....  你的业务代码
```