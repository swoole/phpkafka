# 消费者

## 消费者配置

类名：`longlang\phpkafka\Consumer\ConsumerConfig`

> 支持构造方法传入数组赋值

### 配置参数

| 参数名 | 说明 | 默认值 |
| - | - | - |
| connectTimeout | 连接超时时间（单位：秒，支持小数），为`-1`则不限制 | `-1` |
| sendTimeout | 发送超时时间（单位：秒，支持小数），为`-1`则不限制 | `-1` |
| recvTimeout | 接收超时时间（单位：秒，支持小数），为`-1`则不限制 | `-1` |
| clientId | Kafka 客户端标识，不同的消费者进程请使用不同的设置 | `null` |
| maxWriteAttempts | 最大写入尝试次数 | `3` |
| client | 使用哪个 Kafka 客户端类，默认为`null`时根据场景自动识别 | `null` |
| socket | 使用哪个 Kafka Socket 类，默认为`null`时根据场景自动识别 | `null` |
| broker | broker，格式：`'127.0.0.1:9092'` | `null` |
| bootstrapServers | 别名bootstrapServer，引导服务器，如果配置了该值，会自动连接该服务器，并自动更新 brokers。格式：`'127.0.0.1:9092,127.0.0.1:9093'` 或 `['127.0.0.1:9092','127.0.0.1:9093']` | `null` |
| updateBrokers | 是否自动更新 brokers | `true` |
| interval | 未获取消息到消息时，延迟多少秒再次尝试，默认为`0`则不延迟（单位：秒，支持小数） | `0` |
| groupId | 分组 ID | `null` |
| memberId | 用户 ID | `null` |
| groupInstanceId | 分组实例 ID，不同的消费者进程请使用不同的设置 | `null` |
| sessionTimeout | 如果超时后没有收到心跳信号，则协调器会认为该用户死亡。（单位：秒，支持小数） | `60` |
| rebalanceTimeout | 重新平衡组时，协调器等待每个成员重新加入的最长时间（单位：秒，支持小数）。 | `60` |
| topic | 主题名称，支持同时消费多个主题 | `null` |
| replicaId | 副本 ID | `-1` |
| rackId | 机架编号 | `''` |
| autoCommit | 自动提交 offset | `true` |
| groupRetry | 分组操作，匹配预设的错误码时，自动重试次数 | `5` |
| groupRetrySleep | 分组操作重试延迟，单位：秒 | `1` |
| offsetRetry | 偏移量操作，匹配预设的错误码时，自动重试次数 | `5` |
| groupHeartbeat | 分组心跳时间间隔，单位：秒 | `3` |
| autoCreateTopic | 自动创建主题 | `true` |
| partitionAssignmentStrategy | 消费者分区分配策略，可选：范围分配-`longlang\phpkafka\Consumer\Assignor\RangeAssignor`、轮询分配-`\longlang\phpkafka\Consumer\Assignor\RoundRobinAssignor`、粘性分配-`\longlang\phpkafka\Consumer\Assignor\StickyAssignor` | `longlang\phpkafka\Consumer\Assignor\RangeAssignor` |

## 异步消费（回调）

**代码示例：**

```php
use longlang\phpkafka\Consumer\ConsumeMessage;
use longlang\phpkafka\Consumer\Consumer;
use longlang\phpkafka\Consumer\ConsumerConfig;

function consume(ConsumeMessage $message)
{
    var_dump($message->getKey() . ':' . $message->getValue());
    // $consumer->ack($message); // autoCommit设为false时，手动提交
}
$config = new ConsumerConfig();
$config->setBroker('127.0.0.1:9092');
$config->setTopic('test'); // 主题名称
$config->setGroupId('testGroup'); // 分组ID
$config->setClientId('test'); // 客户端ID，不同的消费者进程请使用不同的设置
$config->setGroupInstanceId('test'); // 分组实例ID，不同的消费者进程请使用不同的设置
$config->setInterval(0.1);
$consumer = new Consumer($config, 'consume');
$consumer->start();
```

## 同步消费

**代码示例：**

```php
use longlang\phpkafka\Consumer\Consumer;
use longlang\phpkafka\Consumer\ConsumerConfig;

$config = new ConsumerConfig();
$config->setBroker('127.0.0.1:9092');
$config->setTopic('test'); // 主题名称
$config->setGroupId('testGroup'); // 分组ID
$config->setClientId('test_custom'); // 客户端ID，不同的消费者进程请使用不同的设置
$config->setGroupInstanceId('test_custom'); // 分组实例ID，不同的消费者进程请使用不同的设置
$consumer = new Consumer($config);
while(true) {
    $message = $consumer->consume();
    if($message) {
        var_dump($message->getKey() . ':' . $message->getValue());
        $consumer->ack($message); // 手动提交
    }
    sleep(1);
}
```
