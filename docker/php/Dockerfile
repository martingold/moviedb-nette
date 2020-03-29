FROM php:7.4-apache
MAINTAINER Martin Gold<martin.gold@volny.cz>

RUN apt-get update && apt-get install -y zlib1g-dev libzip-dev libicu-dev g++ git zip curl libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mysqli intl zip gd bcmath sockets iconv

# Configure xdebug
RUN pecl install xdebug \
        && docker-php-ext-enable xdebug \
        && echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
        && echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
        && echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
        && echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
        && echo 'xdebug.remote_connect_back=1' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
        && echo 'xdebug.idekey=xdebug' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Composer
RUN curl -o composer-installer --silent --show-error https://getcomposer.org/installer \
    && php composer-installer --install-dir=/usr/bin --filename=composer \
    && rm composer-installer

# Set document root
ENV APACHE_DOCUMENT_ROOT=/var/www/html/www
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Enable apache extensions
RUN a2enmod rewrite \
    && a2enmod headers