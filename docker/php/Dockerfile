FROM php:8.1-fpm-alpine

ENV COMPOSER_VERSION=2.5.3

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --version=$COMPOSER_VERSION --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html/app

# Change user pid, gid
ARG UID=1000
ARG GID=1000

RUN \
    # Install build deps
    apk --no-cache add shadow linux-headers ${PHPIZE_DEPS} --virtual .build-deps \
    # Change uid and gid
        && usermod -u $UID www-data \
        && groupmod -g $GID www-data \
    # Install xdebug
    && pecl install -Z xdebug \
        && docker-php-ext-enable xdebug \
    # Remove build deps
    && apk --purge del --no-network .build-deps

# Copy php-ini for production area
RUN cp $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

# Copy xdebug config
COPY /docker/php/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Switch to non-root user
USER www-data

# Copy project files
COPY --chown=www-data:www-data ./app /var/www/html/app
