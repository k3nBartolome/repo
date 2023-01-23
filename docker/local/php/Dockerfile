FROM php:7.4-fpm
# Update APT
RUN apt-get update
# Get Latest Composer
COPY --from=composer:2.2.7 /usr/bin/composer /usr/bin/composer

ARG WWWUSER=selindev
ARG WWWGROUP=1000
ARG PHP_MODE

WORKDIR /cfms
# Run PostgreSQL and MySQL installation of extensions
RUN mv "$PHP_INI_DIR/php.ini-$PHP_MODE" "$PHP_INI_DIR/php.ini" && \
    sed -ri -e 's!((post_max_size|upload_max_filesize) = ).M!\164M!g' $PHP_INI_DIR/php.ini && \
    apt-get install -y \
    libpq-dev \
    && apt-get install -y libicu-dev libpng-dev libzip-dev zip unzip zlib1g-dev libonig-dev \
    && apt-get install -y libxml2-dev graphviz libmcrypt-dev libjpeg62-turbo-dev libfreetype6-dev \
    && docker-php-ext-configure gd \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql pgsql mysqli zip mbstring exif
# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*
# Create non-root user to run composer and artisan commands
RUN useradd -G www-data,root -u $WWWGROUP $WWWUSER
RUN usermod -a -G www-data $WWWUSER

USER $WWWUSER