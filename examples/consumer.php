<?php

use Longyan\Kafka\Consumer\ConsumeMessage;
use Longyan\Kafka\Consumer\Consumer;
use Longyan\Kafka\Consumer\ConsumerConfig;
use Longyan\Kafka\Producer\Producer;
use Longyan\Kafka\Producer\ProducerConfig;

require dirname(__DIR__) . '/vendor/autoload.php';

// $config = new ProducerConfig();
// $config->setBootstrapServer('127.0.0.1:9092');
// $config->setUpdateBrokers(true);
// $config->setAcks(-1);
// $producer = new Producer($config);
// $i = 0;
// while (true) {
//     $producer->send('test', (string) microtime(true), uniqid('', true));
//     var_dump(++$i);
//     sleep(3);
// }

function consume(ConsumeMessage $message)
{
    var_dump($message->getKey() . ':' . $message->getValue());
}
$config = new ConsumerConfig();
$config->setBroker('127.0.0.1:9092');
$config->setTopic('test');
$config->setInterval(0.1);
$consumer = new Consumer($config, 'consume');
$consumer->start();

return;
