FROM php:8.1-apache-bookworm

ARG APP_ROOT=/var/www/html

ENV DEBIAN_FRONTEND=noninteractive \
    COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_NO_INTERACTION=1

RUN apt-get update && apt-get install -y --no-install-recommends \
    curl \
    git \
    libfreetype6-dev \
    libicu-dev \
    libjpeg62-turbo-dev \
    libonig-dev \
    libpng-dev \
    libwebp-dev \
    libxml2-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j"$(nproc)" \
    bcmath \
    exif \
    gd \
    intl \
    mbstring \
    pcntl \
    pdo_mysql \
    zip \
    && a2enmod headers rewrite expires \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
COPY deploy/docker/apache/vhost.conf /etc/apache2/sites-available/000-default.conf

WORKDIR ${APP_ROOT}/project
COPY project/composer.json project/composer.lock ./
RUN composer install \
    --no-dev \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts \
    --ignore-platform-reqs

WORKDIR ${APP_ROOT}
COPY . .

RUN rm -f ${APP_ROOT}/project/bootstrap/cache/config.php ${APP_ROOT}/project/bootstrap/cache/routes-v7.php \
    && mkdir -p \
    ${APP_ROOT}/project/storage \
    ${APP_ROOT}/project/bootstrap/cache \
    ${APP_ROOT}/assets/images \
    ${APP_ROOT}/assets/temp_files \
    && chown -R www-data:www-data \
    ${APP_ROOT}/project/storage \
    ${APP_ROOT}/project/bootstrap/cache \
    ${APP_ROOT}/assets/images \
    ${APP_ROOT}/assets/temp_files \
    && chmod -R ug+rwx \
    ${APP_ROOT}/project/storage \
    ${APP_ROOT}/project/bootstrap/cache \
    ${APP_ROOT}/assets/images \
    ${APP_ROOT}/assets/temp_files

COPY deploy/docker/apache/entrypoint.sh /usr/local/bin/xmerch-entrypoint
RUN chmod +x /usr/local/bin/xmerch-entrypoint

WORKDIR ${APP_ROOT}/project

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/xmerch-entrypoint"]
CMD ["apache2-foreground"]

