FROM php:8.0.3-fpm

RUN apt-get update && apt-get install -y \
        zlib1g-dev \
        libpng-dev \
        libpq-dev \
        curl \
        wget \
        zip \
        unzip \
        git

RUN apt-get update && apt-get install -y \
        libmagickwand-dev --no-install-recommends \
        && pecl install imagick \
        && docker-php-ext-enable imagick

RUN pecl install redis && docker-php-ext-enable redis
RUN docker-php-ext-install pdo pdo_pgsql gd bcmath
RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libzip-dev
RUN docker-php-ext-install zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/

CMD ["php-fpm"]
