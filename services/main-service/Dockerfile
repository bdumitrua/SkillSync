FROM php:8.2-fpm

# Extensions instlling
RUN docker-php-ext-install pdo pdo_mysql
RUN apt-get update && apt-get install -y libzip-dev zlib1g-dev openssl zip unzip git supervisor librdkafka-dev
RUN docker-php-ext-install pdo pdo_mysql zip sockets

# Redis 
# RUN pecl install redis && docker-php-ext-enable redis
RUN pecl update-channels \
    && pecl install redis-6.0.2 \
    && docker-php-ext-enable redis


# Kafka 
RUN pecl install rdkafka-6.0.3 && docker-php-ext-enable rdkafka

# Composer
COPY --from=composer:2.7.4 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Install dependencies
COPY composer.json ./
RUN composer install --no-scripts

COPY . .

COPY ./kafka_consumers.conf /etc/supervisor/conf.d/kafka_consumers.conf
COPY ./start.sh /app/start.sh

EXPOSE 8000
CMD ["/app/start.sh"]


