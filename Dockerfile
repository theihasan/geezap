FROM php:8.2-cli-alpine AS builder

WORKDIR /app

RUN apk update && \
    apk add --no-cache \
    linux-headers \
    curl-dev \
    gmp-dev \
    libxml2-dev \
    libpng-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    gcc g++ \
    autoconf \
    make \
    openssl-dev \
    libzip-dev \
    icu icu-dev && \
    docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ > /dev/null 2>&1 && \
    docker-php-ext-install bcmath pdo_mysql gd sockets zip pcntl intl && \
    echo "Installing redis extension..." && pecl install redis > /dev/null 2>&1 && \
    echo "Installing openswoole extension..." && \
    pecl install -D 'enable-openssl="yes" enable-sockets="yes" enable-hook-curl="yes" enable-http2="yes" enable-mysqlnd="yes" with-postgres="no"' openswoole > /dev/null 2>&1 && \
    wget -q https://getcomposer.org/composer-2.phar -O /usr/local/bin/composer \
    && chmod +x /usr/local/bin/composer

COPY composer.json composer.lock /app/

RUN composer install  \
        --ignore-platform-reqs \
        --no-ansi \
        --no-autoloader \
        --no-dev \
        --no-interaction \
        --no-scripts

COPY . /app

RUN composer dump-autoload --no-ansi --no-dev --no-interaction --optimize

FROM node:22-alpine AS node
WORKDIR /app
COPY package.json package-lock.json /app/
RUN npm install --no-audit --no-fund
COPY . /app
RUN npm run build

FROM php:8.2-cli-alpine
WORKDIR /app

COPY --from=builder /usr/local/lib/php/extensions /usr/local/lib/php/extensions

RUN apk add --no-cache icu-dev libpng-dev freetype-dev libjpeg-turbo-dev libzip-dev && \
    docker-php-ext-enable intl redis bcmath pdo_mysql gd sockets pcntl zip && \
    docker-php-ext-enable --ini-name z-openswoole.ini openswoole && \
    chown -R www-data:www-data /app

COPY --from=builder /usr/local/bin/composer /usr/local/bin/composer
COPY --from=builder /app /app
COPY --from=node /app/public/build /app/public/build

CMD php artisan octane:start --host=0.0.0.0 --port=8000
