FROM php:8.1-fpm-alpine

ENV COMPOSER_VERSION=2.5.3

COPY ./app /ver/www/html/app
COPY ./task /ver/www/html/app/var/tmp/task

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --version=$COMPOSER_VERSION --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html/app

# Change user pid, gid
ARG UID=1000
ARG GID=1000
RUN apk --no-cache add shadow linux-headers ${PHPIZE_DEPS} \
        && usermod -u $UID www-data \
        && groupmod -g $GID www-data \
        && mkdir /var/www/.composer && chown www-data:www-data /var/www/.composer \
    # TODO remove linux and phpize and xdebug
    && pecl install -Z xdebug \
        && docker-php-ext-enable xdebug \
    && apk --purge del shadow linux-headers ${PHPIZE_DEPS}

# Copy php-ini for production area
RUN cp $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

# Copy xdebug config
COPY /docker/php/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Switch to non-root user
USER www-data