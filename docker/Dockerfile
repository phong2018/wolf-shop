FROM composer:2.4.2 AS composer

FROM php:8.1-fpm-alpine3.14 AS base
# Composer settings
ENV COMPOSER_HOME /usr/local/lib/composer
ENV COMPOSER_ALLOW_SUPERUSER 1
# ENV COMPOSER_CACHE_DIR /dev/null

# Install composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Install OS Packages
RUN apk update \
    && apk add \
        # libpng-dev \
        apache2-utils \
        autoconf \
        curl \
        g++ \
        libzip-dev \
        libpng-dev \
        jpeg-dev \
        freetype-dev \
        libjpeg-turbo-dev \
        make \
        nginx \
        postgresql-client \
        postgresql-dev \
        supervisor \
        tini \
        vim \
        zlib-dev \
    && pecl install redis \
    && docker-php-ext-install \
        pdo \
        pdo_pgsql \
        gd \
        zip \
        sockets \
        exif \
    && docker-php-ext-enable redis \
    && docker-php-ext-enable exif \
    && docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd


# persistent / runtime deps
RUN apk add --no-cache \
        wkhtmltopdf \
        xvfb \
        font-noto-cjk font-noto-cjk-extra \
    ;

RUN ln -s /usr/bin/wkhtmltopdf /usr/local/bin/wkhtmltopdf;
RUN chmod +x /usr/local/bin/wkhtmltopdf;

COPY docker/rootfs/ /
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

WORKDIR /var/www/app

# Copy all source files to /usr/src/app for production.
# But available to overwrite by volume option when development.
COPY --chown=nginx . .

RUN chown -R nginx:nginx /var/www/app/storage
RUN chmod -R 777 /var/www/app/storage

# Install Composer packages without development package
RUN composer install

FROM base AS development

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["development"]

FROM base AS webserver

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["webserver"]

FROM base AS scheduler

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["laravel-scheduler"]
