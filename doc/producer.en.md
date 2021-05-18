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

**Default partitioning strategy：**

If partition !== null, then use partition

If partition === null && key !== null, then use crc32(key) % partitions to select partition

If partition === null && key === null, then use Round Robin to select partition

## Send a single message

**Example**

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
