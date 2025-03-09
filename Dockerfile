FROM php:8.2-apache
WORKDIR /var/www/html
RUN apt-get update -y && apt-get install -y libmariadb-dev libicu-dev
RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN apt-get update \
    && apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev \
    # configure the GD extension to include support for JPEG and PNG image formats
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd
