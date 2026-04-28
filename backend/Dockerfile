FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

COPY . .
RUN composer dump-autoload --optimize

FROM php:8.2-cli

RUN apt-get update \
    && apt-get install -y --no-install-recommends curl libonig-dev \
    && docker-php-ext-install mbstring pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY --from=vendor /app /var/www/html

RUN mkdir -p storage/framework/cache storage/framework/views storage/framework/sessions bootstrap/cache \
    && chmod +x docker/entrypoint.sh \
    && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 8000

CMD ["docker/entrypoint.sh"]
