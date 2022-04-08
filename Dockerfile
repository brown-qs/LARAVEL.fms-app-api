FROM php:7.4
RUN apt-get update -y && apt-get install -y openssl zip unzip git htop libonig-dev zlib1g-dev libpng-dev
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN docker-php-ext-install pdo mbstring
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install gd
RUN pecl install redis && docker-php-ext-enable redis

WORKDIR /app
COPY . /app
RUN composer install
COPY docker-files/.localenv-example /app/.localenv

EXPOSE 8080
CMD php -S 0.0.0.0:8080 -t public/
