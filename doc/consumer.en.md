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
| broker | broker format `'127.0.0.1:9092'` | `null` |
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
