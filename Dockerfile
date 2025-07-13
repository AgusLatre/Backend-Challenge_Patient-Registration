FROM php:8.1-fpm

WORKDIR /var/www

# Dependencies
RUN apt-get update && apt-get install -y \
    build-essential libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    locales zip jpegoptim optipng pngquant gifsicle \
    vim unzip git curl libpq-dev

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql gd

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /var/www

RUN chown -R www-data:www-data /var/www

EXPOSE 9000
CMD ["php-fpm"]
