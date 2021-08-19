# Consumer

## Consumer configuration

Class `longlang\phpkafka\Consumer\ConsumerConfig`

> You can pass an array to a constructor.

### Configuration key

| Key | Description | Default |
| - | - | - |
| connectTimeout | Connection timeout(unit: second, decimal). `-1` means no limit. | `-1` |
| sendTimeout | Send timeout(unit: second, decimal). `-1` means no limit. | `-1` |
| recvTimeout | Receive timeout (unit: second, decimal). `-1` means no limit. | `-1` |
| clientId | Kafka client ID. Use different settings for different consumers. | `null` |
| maxWriteAttempts | Maximum attempts to write | `3` |
| client | Kafka client used. `null` by default means auto recognition. | `null` |
| socket | Kafka Socket used. `null` by default means auto recognition. | `null` |
| brokers | Alias is broker. Format: `'127.0.0.1:9092,127.0.0.1:9093'` or `['127.0.0.1:9092','127.0.0.1:9093']` | `null` |
| bootstrapServers | Alias bootstrapServer, used to boot the server. If configured, the server will be connected and brokers updated. Format `'127.0.0.1:9092,127.0.0.1:9093'` or `['127.0.0.1:9092','127.0.0.1:9093']`. | `null` |
| updateBrokers | Auto update brokers. | `true` |
| interval | If the message is not received, try again internals. `0` is default and means no intervals(unit: second, decimal). | `0` |
| groupId | Group ID | `null` |
| memberId | Member ID | `null` |
| groupInstanceId | Group instance ID. Use different settings for different consumers. | `null` |
| sessionTimeout | If no heartbeat sent out after the timeout, the group coordinator will consider it dead. (unit: second, decimal) | `60` |
| rebalanceTimeout | The maximum time the coordinator waits for consumers to join. (unit: second, decimal) | `60` |
| topic | Topic name. Suppoprt multiple topics consumed simultaneously. | `null` |
| replicaId | Replica ID | `-1` |
| rackId | Rack ID | `''` |
| autoCommit | Auto commit offset | `true` |
| groupRetry | Group retries allowed if matching an error code. | `5` |
| groupRetrySleep | Group retry sleep time. (unit: second) | `1` |
| offsetRetry | Offset retries if matching an error code. | `5` |
| groupHeartbeat | Group heartbeat intervals. (unit: second) | `3` |
| autoCreateTopic | Auto create topic. | `true` |
| partitionAssignmentStrategy | Consumer partition assignment strategy. Optional: Range-`longlang\phpkafka\Consumer\Assignor\RangeAssignor`, RoundRobin-`\longlang\phpkafka\Consumer\Assignor\RoundRobinAssignor`, Sticky-`\longlang\phpkafka\Consumer\Assignor\StickyAssignor`. |
| exceptionCallback | This callback is called when an exception that cannot be thrown by the `recv()` coroutine is encountered. Format: `function(\Exception $e){}` | `null` |
| minBytes | Min bytes | `1` |
| maxBytes | Max bytes | `128 * 1024 * 1024` |
| maxWait | The maximum time. (unit: second, decimal) | `1` |
| sasl |  SASL authentication Info. If the field is null, it will not authenticate with SASL [detail](#SASL-Support) | `[]`|
| ssl |  SSL Connect Info. If the field is null, it will not use SSL [detail](#SSL-Support) | `null`|

## Asynchronous (callback)

**Example**

```php
use longlang\phpkafka\Consumer\ConsumeMessage;
use longlang\phpkafka\Consumer\Consumer;
use longlang\phpkafka\Consumer\ConsumerConfig;

function consume(ConsumeMessage $message)
{
    var_dump($message->getKey() . ':' . $message->getValue());
    // $consumer->ack($message); // If autoCommit is set as false, commit manually.
}
$config = new ConsumerConfig();
$config->setBroker('127.0.0.1:9092');
$config->setTopic('test'); // topic
$config->setGroupId('testGroup'); // group ID
$config->setClientId('test'); // client ID. Use different settings for different consumers.
$config->setGroupInstanceId('test'); // group instance ID. Use different settings for different consumers.
$config->setInterval(0.1);
$consumer = new Consumer($config, 'consume');
$consumer->start();
```

## Synchronous

**Example**

```php
use longlang\phpkafka\Consumer\Consumer;
use longlang\phpkafka\Consumer\ConsumerConfig;

$config = new ConsumerConfig();
$config->setBroker('127.0.0.1:9092');
$config->setTopic('test'); // topic
$config->setGroupId('testGroup'); // group ID
$config->setClientId('test_custom'); // client ID. Use different settings for different consumers.
$config->setGroupInstanceId('test_custom'); // group instance ID. Use different settings for different consumers.
$consumer = new Consumer($config);
while(true) {
    $message = $consumer->consume();
    if($message) {
        var_dump($message->getKey() . ':' . $message->getValue());
        $consumer->ack($message); // commit manually
    }
    sleep(1);
}
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
use longlang\phpkafka\Consumer\Consumer;
use longlang\phpkafka\Consumer\ConsumerConfig;

$config = new ConsumerConfig();
// .... Your Othor Config
$config->setSasl([
    "type"=>\longlang\phpkafka\Sasl\PlainSasl::class,
    "username"=>"admin",
    "password"=>"admin-secret"
]);
$consumer = new Consumer($config);
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
use longlang\phpkafka\Consumer\Consumer;
use longlang\phpkafka\Consumer\ConsumerConfig;
use longlang\phpkafka\Config\SslConfig;

$config = new ConsumerConfig();
// .... Your Othor Config
$sslConfig = new SslConfig();
$sslConfig->setOpen(true);
$sslConfig->setVerifyPeer(true);
$sslConfig->setAllowSelfSigned(true);
$sslConfig->setCafile("/kafka-client/.github/kafka/cert/ca-cert");
$config->setSsl($sslConfig);
$consumer = new Consumer($config);
// ....  Your Business Code
```