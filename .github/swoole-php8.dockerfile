ARG SWOOLE_DOCKER_VERSION
FROM zhangrunyu/swoole-php8:${SWOOLE_DOCKER_VERSION}

# apt
RUN apt update

# git
RUN apt install -y git --no-install-recommends


# snappy
RUN git clone --recursive --depth=1 https://github.com/kjdev/php-ext-snappy.git \
    && cd php-ext-snappy \
    && phpize \
    && ./configure \
    && make -j \
    && make install \
    && cd ../ \
    && rm -r php-ext-snappy \
    && echo "extension = snappy.so" >> $(php -r "echo php_ini_loaded_file();")

# lz4
RUN git clone --recursive --depth=1 https://github.com/kjdev/php-ext-lz4.git \
    && cd php-ext-lz4 \
    && phpize \
    && ./configure \
    && make -j \
    && make install \
    && cd ../ \
    && rm -r php-ext-lz4 \
    && echo "extension = lz4.so" >> $(php -r "echo php_ini_loaded_file();")

# zstd
RUN git clone --recursive --depth=1 https://github.com/kjdev/php-ext-zstd.git \
    && cd php-ext-zstd \
    && phpize \
    && ./configure \
    && make -j \
    && make install \
    && cd ../ \
    && rm -r php-ext-zstd \
    && echo "extension = zstd.so" >> $(php -r "echo php_ini_loaded_file();")
