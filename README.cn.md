# longlang/phpkafka

**本项目已迁移到 [swoole/library](https://github.com/swoole/library)，此仓库将存档，不再更新。**

客户端命名空间现为 `Swoole\Kafka`。类通过 Composer 加载，不会编译进 Swoole 扩展。

```bash
composer require swoole/library
```

文档：[消费者](https://github.com/swoole/library/blob/master/docs/kafka/consumer.md)、[生产者](https://github.com/swoole/library/blob/master/docs/kafka/producer.md)。示例：[examples/kafka](https://github.com/swoole/library/tree/master/examples/kafka)。

请把 `longlang\phpkafka\` 替换为 `Swoole\Kafka\`。下文是本仓库发布的最后一版说明。

[![Latest Version](https://poser.pugx.org/longlang/phpkafka/v/stable)](https://packagist.org/packages/longlang/phpkafka)
[![Php Version](https://img.shields.io/badge/php-%3E=7.1-brightgreen.svg)](https://secure.php.net/)
[![License](https://img.shields.io/github/license/longyan/phpkafka.svg)](https://github.com/longyan/phpkafka/blob/master/LICENSE)

## 简介

[English](README.md) | 简体中文

PHP Kafka 客户端，支持 PHP-FPM、Swoole 环境使用。

通讯协议的结构基于 Java 版本中的 JSON 文件生成，这可能是有史以来支持消息类型最多的 PHP Kafka 客户端，支持全部 50 个 API。

> 目前已实现消息的生成及消费，本组件仍处于开发及测试阶段。

## 功能特性

- [x] 支持全部 50 个 API
- [x] 消息压缩支持 (gzip、snappy、lz4、zstd)
- [x] PHP-FPM、Swoole 智能环境识别兼容
- [x] 生产者类
- [x] 消费者类
- [x] SASL 鉴权
- [x] SSL 加密通信
- [ ] 更多功能的封装及测试用例编写

## 环境要求

- PHP >= 7.1
- Kafka >= 1.0.0
- Swoole >= 4.5 (可选)

## 安装

`composer require longlang/phpkafka`

## 文档及示例

- [生产者](doc/producer.md)

- [消费者](doc/consumer.md)

示例代码请参考 `examples` 目录
