FROM php:8.4-cli

WORKDIR /var/www/html

RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip zip libzip-dev libonig-dev libpng-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring zip xml \
    && rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html

EXPOSE 8000
CMD ["bash", "-lc", "composer install --no-interaction --prefer-dist && php artisan key:generate --force && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8000"]
