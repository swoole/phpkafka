# longlang/phpkafka

**This project has moved to [swoole/library](https://github.com/swoole/library) and this repository will be archived.**

The client now lives in the `Swoole\Kafka` namespace. It is loaded through Composer and is not compiled into the Swoole extension.

```bash
composer require swoole/library
```

Documentation: [consumer](https://github.com/swoole/library/blob/master/docs/kafka/consumer.en.md), [producer](https://github.com/swoole/library/blob/master/docs/kafka/producer.en.md). Examples: [examples/kafka](https://github.com/swoole/library/tree/master/examples/kafka).

Replace `longlang\phpkafka\` with `Swoole\Kafka\`. The code below is the last version published from this repository.


[![Latest Version](https://poser.pugx.org/longlang/phpkafka/v/stable)](https://packagist.org/packages/longlang/phpkafka)
[![Php Version](https://img.shields.io/badge/php-%3E=7.1-brightgreen.svg)](https://secure.php.net/)
[![License](https://img.shields.io/github/license/longyan/phpkafka.svg)](https://github.com/longyan/phpkafka/blob/master/LICENSE)

## Introduction

English | [简体中文](README.cn.md)

PHP Kafka client is used in PHP-FPM and Swoole.

The communication protocol is based on the JSON file in Java. PHP Kafka client supports 50 APIs, which might be one that supports the most message types ever.

> The produce and the consume of messages are implemented. The component is in developing and testing.

## Features

- [x] Support all 50 APIs
- [x] Message compression 
- [x] PHP-FPM and Swoole compatible
- [x] Producer
- [x] Consumer
- [x] SASL
- [x] SSL
- [ ] More features and test cases

## Environment

- PHP >= 7.1
- Kafka >= 1.0.0
- Swoole >= 4.5 (optional)

## Installation

`composer require longlang/phpkafka`

## Documentation and Examples

- [Producer](doc/producer.en.md)

- [Consumer](doc/consumer.en.md)

Refer to `examples` for code examples. 
