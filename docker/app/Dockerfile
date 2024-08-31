FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libssl-dev \
    pkg-config \
    libfreetype6-dev \
    libonig-dev \
    libzip-dev \
    redis-server \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . /var/www

RUN composer install --no-dev --optimize-autoloader
RUN chown -R www-data:www-data /var/www

EXPOSE 9000

# Запуск PHP-FPM
CMD ["php-fpm"]
