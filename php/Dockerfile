FROM php:7.2.7-fpm-alpine3.7
ADD auth.php /usr/local/etc/
RUN apk update; \
    apk upgrade;
RUN docker-php-ext-install mysqli
