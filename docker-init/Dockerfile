# ============================================
# STAGE 1: Composer (PHP 8.4 environment)
# ============================================
FROM php:8.4-cli AS vendor

# Install dependencies for composer & PHP extensions needed during build
RUN apt-get update && apt-get install -y \
    unzip git \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libwebp-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install zip gd 

# Install composer 
RUN curl -sS https://getcomposer.org/installer \
    | php -- --install-dir=/usr/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files only (faster caching)
COPY composer.json ./
# COPY composer.json composer.lock ./

# Install Laravel vendor dependencies without dev --no-dev 
RUN composer install --no-scripts --prefer-dist --no-interaction

# Copy full application
COPY . .

# Optimize autoload
RUN composer dump-autoload --optimize

# ============================================
# STAGE 2: Final PHP-Apache runtime
# ============================================
FROM php:8.4-apache-bookworm

ENV TZ=Asia/Jakarta \
    APACHE_DOCUMENT_ROOT=/var/www/html/public \
    COMPOSER_ALLOW_SUPERUSER=1

# --------------------------------------------
# Install required PHP extensions and packages
# --------------------------------------------
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libwebp-dev \
        libzip-dev \
        libpq-dev \
        unzip \
        cron \
        supervisor \
        tzdata \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install gd zip pdo pdo_mysql mysqli opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# --------------------------------------------
# Apache config
# --------------------------------------------
RUN a2enmod rewrite

RUN sed -ri -e "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" \
    /etc/apache2/sites-available/*.conf && \
    sed -ri -e "s!/var/www/!${APACHE_DOCUMENT_ROOT}!g" \
    /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# --------------------------------------------
# Copy application (except vendor)
# --------------------------------------------
WORKDIR /var/www/html
COPY --chown=www-data:www-data . .

# Copy vendor dari stage build (PHP 8.3 compatible)
COPY --from=vendor --chown=www-data:www-data /var/www/html/vendor ./vendor

# Laravel setup
RUN php generate-image-version.php && \
    cp .env.example .env && \
    php artisan key:generate && \
    php artisan storage:link && \
    chmod -R 0755 storage bootstrap/cache


# --------------------------------------------
# Cron
# --------------------------------------------
COPY --chown=www-data:www-data cron-job /etc/cron.d/cron-job
RUN chmod 0644 /etc/cron.d/cron-job && \
    crontab /etc/cron.d/cron-job

# --------------------------------------------
# Supervisor
# --------------------------------------------
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]
