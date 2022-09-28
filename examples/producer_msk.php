<?php

declare(strict_types=1);

use longlang\phpkafka\Producer\Producer;
use longlang\phpkafka\Producer\ProducerConfig;
use longlang\phpkafka\Protocol\RecordBatch\RecordHeader;
use longlang\phpkafka\Config\SslConfig;
use longlang\phpkafka\Sasl\AwsMskIamSasl;

require dirname(__DIR__) . '/vendor/autoload.php';
$sslConfig = new SslConfig();
$sslConfig->setOpen(true);
$sslConfig->setCompression(true);

$config = new ProducerConfig();
$config->setBootstrapServer('b-1.fakemskcluster.kafka.eu-west-1.amazonaws.com:9098,b-2.fakemskcluster.kafka.eu-west-1.amazonaws.com:9098');
$config->setUpdateBrokers(true);
$config->setAcks(-1);
$config->setSasl([
    "type" => AwsMskIamSasl::class,
    "region" => "eu-west-1",
    "expiration" => "+5 minutes"
]);
$config->setSsl($sslConfig);

$producer = new Producer($config);
$topic = 'MSKTutorialTopic';
$value = "It Works!!!";
$key = uniqid('', true);
$producer->send($topic, $value, $key);

for ($i = 1; $i <= 10; $i++) {
    $value = "Message: " . $i;
    $headers = [
        'key1' => 'value1',
        (new RecordHeader())->setHeaderKey('key2')->setValue('value2'),
    ];
    $producer->send($topic, $value, $key, $headers);
}

