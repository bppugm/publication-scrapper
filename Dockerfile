FROM php:7.4-fpm

WORKDIR /var/www/html/

COPY composer.lock composer.json /var/www/html/

RUN apt-get update && apt-get install -y \
    libzip-dev \
    build-essential \
    libcurl4-openssl-dev pkg-config libssl-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev

RUN docker-php-ext-install pdo_mysql zip exif pcntl

# Add and enable mongodb extension
RUN pecl install -o -f mongodb \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable mongodb

RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd

# Install Composer
RUN curl --silent --show-error https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN useradd -ms /bin/bash scrapper
RUN usermod -o -u 1000 scrapper
RUN groupmod -o -g 1000 scrapper

COPY .docker/scrapper.ini "$PHP_INI_DIR/conf.d/"

USER scrapper
RUN composer install --no-autoloader
CMD [ "php-fpm" ]
