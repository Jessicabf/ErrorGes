FROM php:8.1-fpm

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && docker-php-ext-install pdo pdo_mysql

CMD ["php-fpm", "-F"]
