#!/bin/bash

BASE_DIR="/kafka-client/.github/kafka/cert"
PASSWORD='phpkafka123456'


mkdir -p ${BASE_DIR}
mkdir -p ${BASE_DIR}/kafka1
mkdir -p ${BASE_DIR}/kafka2

cd ${BASE_DIR} || exit 1


# 生成CA证书
openssl req -new -x509 -keyout ca-key -out ca-cert -days 36500 -subj "/CN=Kakfa ROOT CA/OU=PHPKafka/O=Swoole/L=BeiJing/ST=BeiJing/C=CN" -passin "pass:${PASSWORD}" -passout "pass:${PASSWORD}"
keytool -keystore client.truststore.jks -alias CARoot -import -file ca-cert -keypass ${PASSWORD}
keytool -keystore server.truststore.jks -alias CARoot -import -file ca-cert -keypass ${PASSWORD}


# 生成服务端证书
#kafka1
keytool -keystore ./kafka1/server.keystore.jks -alias kafka -validity 36500 -genkey -keypass phpkafka123456 -keyalg RSA -dname "CN=kafka1,OU=PHPKafka,O=Swoole,L=BeiJing,S=BeiJing,C=CN" -storepass phpkafka123456
keytool -keystore ./kafka1/server.keystore.jks -alias kafka -certreq -file ./kafka1/cert-file -keypass ${PASSWORD}
openssl x509 -req -CA ca-cert -CAkey ca-key -in ./kafka1/cert-file -out ./kafka1/cert-signed -days 36500 -CAcreateserial -passin pass:${PASSWORD}
keytool -keystore ./kafka1/server.keystore.jks -alias CARoot -import -file ca-cert -keypass ${PASSWORD}
keytool -keystore ./kafka1/server.keystore.jks -alias kafka -import -file ./kafka1/cert-signed -keypass ${PASSWORD}

#kafka2
keytool -keystore ./kafka2/server.keystore.jks -alias kafka -validity 36500 -genkey -keypass ${PASSWORD} -keyalg RSA -dname "CN=kafka2,OU=PHPKafka,O=Swoole,L=BeiJing,S=BeiJing,C=CN" -storepass phpkafka123456
keytool -keystore ./kafka2/server.keystore.jks -alias kafka -certreq -file ./kafka2/cert-file -keypass ${PASSWORD}
openssl x509 -req -CA ca-cert -CAkey ca-key -in ./kafka2/cert-file -out ./kafka2/cert-signed -days 36500 -CAcreateserial -passin pass:${PASSWORD}
keytool -keystore ./kafka2/server.keystore.jks -alias CARoot -import -file ca-cert -keypass ${PASSWORD}
keytool -keystore ./kafka2/server.keystore.jks -alias kafka -import -file ./kafka2/cert-signed -keypass ${PASSWORD}

