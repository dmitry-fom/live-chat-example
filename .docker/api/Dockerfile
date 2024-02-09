FROM php:fpm-alpine

WORKDIR /api/

COPY ./../../api/ ./

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apk update  \
    && apk add php-bcmath \
    mc \
    nano \
    php-json \
    php-mbstring \
    php-tokenizer \
    php-xml \
    php-curl \
    libmemcached-dev \
    libpng-dev \
    postgresql-dev

RUN docker-php-ext-install pdo pdo_pgsql pgsql

EXPOSE 9000

ENTRYPOINT ["/bin/sh", "entrypoint.sh"]