ARG PHP_VERSION=7.3
ARG BASE_VERSION=buster
FROM srijanlabs/php-cli:${PHP_VERSION}-${BASE_VERSION} as builder
COPY composer.json composer.lock /app/
COPY patches ./patches
RUN composer install --no-dev --prefer-dist --no-progress --no-suggest --no-interaction --optimize-autoloader

FROM srijanlabs/php-fpm-apache:${PHP_VERSION}-${BASE_VERSION} as fpm
USER root
ENV LIBRDKAFKA_VERSION v0.11.0
ENV BUILD_DEPS \
        build-essential \
        git \
        libsasl2-dev \
        libssl-dev \
        python-minimal \
        zlib1g-dev \
        php-pear
RUN apt-get update \
    && apt-get install -y --no-install-recommends ${BUILD_DEPS} \
    && cd /tmp \
    && git clone \
        --branch ${LIBRDKAFKA_VERSION} \
        --depth 1 \
        https://github.com/edenhill/librdkafka.git \
    && cd librdkafka \
    && ./configure \
    && make \
    && make install \
    && pecl install rdkafka-3.0.3 \
    && echo extension=rdkafka.so >> /etc/php/7.3/fpm/php.ini \
    && echo extension=rdkafka.so >> /etc/php/7.3/cli/php.ini \
    && rm -rf /tmp/librdkafka \
    && apt-get purge \
        -y --auto-remove \
        -o APT::AutoRemove::RecommendsImportant=false \
        ${BUILD_DEPS}
USER continua
COPY --from=builder --chown=continua /app  /app
COPY --chown=continua . /app
