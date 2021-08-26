# longlang/phpkafka

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
