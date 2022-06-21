<?php

declare(strict_types=1);

use longlang\phpkafka\Producer\Producer;
use longlang\phpkafka\Producer\ProducerConfig;
use longlang\phpkafka\Protocol\RecordBatch\RecordHeader;
use longlang\phpkafka\Sasl\AwsMskIamSasl;

require dirname(__DIR__) . '/vendor/autoload.php';

$config = new ProducerConfig();
$config->setBootstrapServer('127.0.0.1:9092');
$config->setUpdateBrokers(true);
$config->setAcks(-1);
$config->setSasl([
    "type"=> AwsMskIamSasl::class,
    "host"=>"localhost",
    "region"=>"eu-west-1"
]);
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
