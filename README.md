# longlang/phpkafka

[![Latest Version](https://poser.pugx.org/longlang/phpkafka/v/stable)](https://packagist.org/packages/longlang/phpkafka)
[![Php Version](https://img.shields.io/badge/php-%3E=7.1-brightgreen.svg)](https://secure.php.net/)
[![IMI License](https://img.shields.io/github/license/longlang/phpkafka.svg)](https://github.com/longlang/phpkafka/blob/master/LICENSE)

## 简介

PHP Kafka 客户端，支持 PHP-FPM、Swoole 环境使用。

通讯协议的结构基于 Java 版本中的 JSON 文件生成，这可能是有史以来支持消息类型最多的 PHP Kafka 客户端，支持全部 50 个 API。

> 目前已实现消息的生成及消费，本组件仍处于开发及测试阶段。

## 环境要求

- PHP >= 7.1
- Kafka >= 1.0.0 (更旧的版本正在考虑是否支持)
- Swoole >= 4.5 (可选) 

## 安装

`composer require longlang/phpkafka`

## 文档及示例

文档正在编写中……

示例代码请参考 `examples` 目录

## TODO

- [ ] 消息压缩
- [ ] SSL 加密通信
- [ ] SASL 鉴权
- [ ] Record Batch v0、v1 支持，目前仅支持了 v2 (待定)
- [ ] 更多功能的封装及测试用例编写
