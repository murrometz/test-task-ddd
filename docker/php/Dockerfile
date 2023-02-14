FROM php:8.1-fpm-alpine

ENV COMPOSER_VERSION=2.5.3

COPY ./app /app

#TODO DELETE
RUN apk add bash

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --version=$COMPOSER_VERSION --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

# Change user pid, gid
ARG UID=1000
ARG GID=1000
RUN apk --no-cache add shadow \
    && usermod -u $UID www-data \
    && groupmod -g $GID www-data \
    && mkdir /var/www/.composer && chown www-data:www-data /var/www/.composer \
    && apk --purge del shadow

# switch to non-root user
USER www-data