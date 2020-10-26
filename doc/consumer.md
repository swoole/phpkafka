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
| clientId | Kafka 客户端标识 | `null` |
| maxWriteAttempts | 最大写入尝试次数 | `3` |
| client | 使用哪个 Kafka 客户端类，默认为`null`时根据场景自动识别 | `null` |
| socket | 使用哪个 Kafka Socket 类，默认为`null`时根据场景自动识别 | `null` |
| broker | broker，格式：`'127.0.0.1:9092'` | `null` |
| interval | 未获取消息到消息时，延迟多少秒再次尝试，默认为`0`则不延迟（单位：秒，支持小数） | `0` |
| groupId | 分组 ID | `null` |
| memberId | 用户 ID | `null` |
| groupInstanceId | 分组实例 ID | `null` |
| protocols | 协议列表 | `[]` |
| sessionTimeout | 如果超时后没有收到心跳信号，则协调器会认为该用户死亡。（单位：秒，支持小数） | `60` |
| rebalanceTimeout | 重新平衡组时，协调器等待每个成员重新加入的最长时间（单位：秒，支持小数）。 | `60` |
| topic | 主题名称 | `null` |
| partitions | 分区列表 | `[0]` |
| replicaId | 副本 ID | `-1` |
| rackId | 机架编号 | `''` |
| autoCommit | 自动提交 offset | `true` |

## 异步消费（回调）

**代码示例：**

```php
use longlang\phpkafka\Consumer\ConsumeMessage;
use longlang\phpkafka\Consumer\Consumer;
use longlang\phpkafka\Consumer\ConsumerConfig;

function consume(ConsumeMessage $message)
{
    var_dump($message->getKey() . ':' . $message->getValue());
    // $consumer->ack($message->getPartition()); // autoCommit设为false时，手动提交
}
$config = new ConsumerConfig();
$config->setBroker('127.0.0.1:9092');
$config->setTopic('test');
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
$config->setTopic('test');
$consumer = new Consumer($config);
while(true)
{
    $message = $consumer->consume();
    if($message)
    {
        var_dump($message->getKey() . ':' . $message->getValue());
        $consumer->ack($message->getPartition()); // 手动提交
    }
    sleep(1);
}
```
