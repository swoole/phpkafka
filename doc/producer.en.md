# Producer

## Producer configuration

Class `longlang\phpkafka\Producer\ProducerConfig`

> You can pass an array to a constructor.

### Configuration keys

| Key | Description | Default |
| - | - | - |
| connectTimeout | Connection timeout(unit: second, decimal). `-1` means no limit. | `-1` |
| sendTimeout | Connection timeout(unit: second, decimal). `-1` means no limit. | `-1` |
| recvTimeout | Connection timeout(unit: second, decimal).`-1` means no limit. | `-1` |
| clientId | Kafka client ID | `null` |
| maxWriteAttempts | Maximum attempts to write | `3` |
| client | Kafka client used. `null` by default means auto recognition. | `null` |
| socket | Kafka Socket used. `null` by default means auto recognition. | `null` |
| brokers | Configure brokers. If configure it manually, set `updateBrokers` to `true`. Format: `'127.0.0.1:9092,127.0.0.1:9093'` or `['127.0.0.1:9092','127.0.0.1:9093']` | `null` |
| bootstrapServers | Alias bootstrapServer, used to boot the server. If configured, the server will be connected and brokers updated. Format: `'127.0.0.1:9092,127.0.0.1:9093'` or `['127.0.0.1:9092','127.0.0.1:9093']` | `null` |
| updateBrokers | Auto update brokers | `true` |
| acks | The producer acknowledges the leader before responding. 0 means not confirmed, 1 means the leader confirmed, -1 means ISR. | `0` |
| producerId | producer ID | `-1` |
| producerEpoch | producer Epoch | `-1` |
| partitionLeaderEpoch | partition Leader Epoch | `-1` |
| autoCreateTopic | auto create topic | `true` |
| exceptionCallback | This callback is called when an exception that cannot be thrown by the `recv()` coroutine is encountered. Format: `function(\Exception $e){}` | `null` |
| partitioner | Partitioning strategy |  Default: `\longlang\phpkafka\Producer\Partitioner\DefaultPartitioner` |
| produceRetry | Produce message retries allowed if matching an error code. | `3` |
| produceRetrySleep | Produce message retry sleep time. (unit: second) | `0.1` |
| sasl |  SASL authentication Info. If the field is null, it will not authenticate with SASL [detail](#SASL-Support) | `[]`|
| ssl |  SSL Connect Info. If the field is null, it will not use SSL [detail](#SSL-Support) | `null` |

**Default partitioning strategy：**

If partition !== null, then use partition

If partition === null && key !== null, then use crc32(key) % partitions to select partition

If partition === null && key === null, then use Round Robin to select partition

## Send a single message

**Example**

```php
use longlang\phpkafka\Producer\Producer;
use longlang\phpkafka\Producer\ProducerConfig;
use longlang\phpkafka\Protocol\RecordBatch\RecordHeader;

$config = new ProducerConfig();
$config->setBootstrapServer('127.0.0.1:9092');
$config->setUpdateBrokers(true);
$config->setAcks(-1);
$producer = new Producer($config);
$topic = 'test';
$value = (string) microtime(true);
$key = uniqid('', true);
$producer->send('test', $value, $key);

// set headers
// key-value or use RecordHeader
$headers = [
    'key1' => 'value1',
    (new RecordHeader())->setHeaderKey('key2')->setValue('value2'),
];
$producer->send('test', $value, $key, $headers);
```

## Send batch messages

**Example**

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

## SASL Support

### Configuration

| Key | Description | Default |
| - | - | - |
| type | SASL Authentication Type. PLAIN is ``\longlang\phpkafka\Sasl\PlainSasl::class``| ''|
| username | username  | '' |
| password | password  | '' |

**Example**

```php
use longlang\phpkafka\Producer\ProducerConfig;
use longlang\phpkafka\Producer\Producer;

$config = new ProducerConfig();
// .... Your Other Config
$config->setSasl([
    "type"=>\longlang\phpkafka\Sasl\PlainSasl::class,
    "username"=>"admin",
    "password"=>"admin-secret"
]);
$producer = new Producer($config);
// ....  Your Business Code
```

## SSL Support

Class `longlang\phpkafka\Config\SslConfig`

> You can pass an array to a constructor.

### Configuration keys

| Key | Description | Default |
| - | - | - |
| open  | Enable SSL  | `false` |
| compression | TLS compression. | `true`  |
| certFile |Path to local certificate file on filesystem. |`''`|
| keyFile |Path to local private key file on filesystem|`''`|
| passphrase |  Passphrase with which your ``certFile`` file was encoded. | `''`|
| peerName |  Peer name to be used. If this value is not set, then the name is remote Host | `''`|
| verifyPeer |Require verification of SSL certificate used. | `false` |
| verifyPeerName |Require verification of peer name.| `false` |
| verifyDepth | Abort if the certificate chain is too deep. | `0`|
| allowSelfSigned | Allow self-signed certificates. | `false` | 
| cafile | Location of Certificate Authority file on local filesystem which should be used  | `''`|
| capath  | If cafile is not specified or if the certificate is not found there, the directory pointed to by capath is searched for a suitable certificate. capath must be a correctly hashed certificate directory. | `''`|

**Example**

```php
use longlang\phpkafka\Producer\ProducerConfig;
use longlang\phpkafka\Producer\Producer;
use longlang\phpkafka\Config\SslConfig;

$config = new ProducerConfig();
// .... Your Othor Config
$sslConfig = new SslConfig();
$sslConfig->setOpen(true);
$sslConfig->setVerifyPeer(true);
$sslConfig->setAllowSelfSigned(true);
$sslConfig->setCafile("/kafka-client/.github/kafka/cert/ca-cert");
$config->setSsl($sslConfig);
$producer = new Producer($config);
// ....  Your Business Code
```
