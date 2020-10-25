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
| brokers | 手动配置 brokers 列表，如果要使用手动配置，请把`updateBrokers`设为`true`。格式：`['127.0.0.1:9092','127.0.0.1:9093']` | `null` |
| bootstrapServer | 引导服务器，如果配置了该值，会自动连接该服务器，并自动更新 brokers | `null` |
| updateBrokers | 是否自动更新 brokers | `true` |
| acks | 生产者要求领导者，在确认请求完成之前已收到的确认数值。允许的值：0表示无确认，1表示仅领导者，-1表示完整的ISR。 | `0` |
| producerId | 生产者 ID | `-1` |
| producerEpoch | 生产者 Epoch | `-1` |
| partitionLeaderEpoch | 分区 Leader Epoch | `-1` |

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
$producer->sendBatch([
    new ProduceMessage($topic, 'v1', 'k1'),
    new ProduceMessage($topic, 'v2', 'k2'),
]);
```
