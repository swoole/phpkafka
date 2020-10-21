ARG SWOOLE_VERSION
ARG PHP_VERSION
FROM phpswoole/swoole:${SWOOLE_VERSION}-php${PHP_VERSION}

# apt
RUN apt update

# git
RUN apt install -y git --no-install-recommends

# bcmath
RUN docker-php-ext-install bcmath > /dev/null

# snappy
RUN git clone --recursive --depth=1 https://github.com/kjdev/php-ext-snappy.git \
    && cd php-ext-snappy \
    && phpize \
    && ./configure \
    && make -j \
    && make install \
    && cd ../ \
    && rm -r php-ext-snappy \
    && docker-php-ext-enable snappy

# lz4
RUN git clone --recursive --depth=1 https://github.com/kjdev/php-ext-lz4.git \
    && cd php-ext-lz4 \
    && phpize \
    && ./configure \
    && make -j \
    && make install \
    && cd ../ \
    && rm -r php-ext-lz4 \
    && docker-php-ext-enable lz4

# zstd
RUN git clone --recursive --depth=1 https://github.com/kjdev/php-ext-zstd.git \
    && cd php-ext-zstd \
    && phpize \
    && ./configure \
    && make -j \
    && make install \
    && cd ../ \
    && rm -r php-ext-zstd \
    && docker-php-ext-enable zstd
