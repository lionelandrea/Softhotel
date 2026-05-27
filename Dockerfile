FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    git unzip zip libzip-dev libicu-dev default-mysql-client \
    && docker-php-ext-install pdo pdo_mysql intl zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN php -d memory_limit=-1 bin/console tailwind:build || true

RUN php bin/console cache:clear --env=prod || true

EXPOSE 10000

CMD php -S 0.0.0.0:${PORT:-10000} -t public