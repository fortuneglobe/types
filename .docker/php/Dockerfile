FROM php:8.1-fpm-alpine
ENV XDEBUG_VERSION 3.1.5

RUN apk add --no-cache nano icu-dev $PHPIZE_DEPS
RUN pecl install xdebug-${XDEBUG_VERSION}
RUN docker-php-ext-enable xdebug

CMD ["php-fpm"]
