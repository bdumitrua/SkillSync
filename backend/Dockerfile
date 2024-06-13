FROM php:8.2-fpm

# Extensions instlling

RUN apt-get update && apt-get install -y libzip-dev zlib1g-dev openssl zip unzip git supervisor libwebsockets-dev
RUN docker-php-ext-install pdo pdo_mysql zip sockets 
RUN docker-php-ext-install pcntl
RUN docker-php-ext-configure pcntl --enable-pcntl

# Redis 
# RUN pecl install redis && docker-php-ext-enable redis
RUN pecl update-channels \
    && pecl install redis-6.0.2 \
    && docker-php-ext-enable redis

# Composer
COPY --from=composer:2.7.4 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Install dependencies
COPY composer.json ./
RUN composer install --no-scripts

COPY . .

COPY ./queue.conf /etc/supervisor/conf.d/queue.conf
COPY ./websockets.conf /etc/supervisor/conf.d/websockets.conf

COPY ./start.sh /app/start.sh

EXPOSE 8000
CMD ["/app/start.sh"]


