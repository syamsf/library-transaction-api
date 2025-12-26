# STAGE 1: Builder
FROM php:8.5-fpm AS builder

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY src/composer.json /var/www/html/

RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --prefer-dist \
    --no-interaction

COPY src/ /var/www/html/

RUN composer dump-autoload --optimize --no-dev

FROM php:8.5-fpm

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    apache2 \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    libzip-dev \
    curl \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip

RUN pecl install redis && docker-php-ext-enable redis

RUN a2enmod rewrite proxy proxy_fcgi

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

RUN echo "pm = dynamic" >> /usr/local/etc/php-fpm.d/www.conf && \
    echo "pm.max_children = 50" >> /usr/local/etc/php-fpm.d/www.conf && \
    echo "pm.start_servers = 10" >> /usr/local/etc/php-fpm.d/www.conf && \
    echo "pm.min_spare_servers = 5" >> /usr/local/etc/php-fpm.d/www.conf && \
    echo "pm.max_spare_servers = 20" >> /usr/local/etc/php-fpm.d/www.conf && \
    echo "pm.max_requests = 500" >> /usr/local/etc/php-fpm.d/www.conf

COPY --from=builder --chown=www-data:www-data /var/www/html /var/www/html

RUN echo '<VirtualHost *:80>\n\
    ServerName localhost\n\
    DocumentRoot /var/www/html/public\n\
    \n\
    <Directory /var/www/html/public>\n\
        Options Indexes FollowSymLinks\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
    \n\
    ErrorLog ${APACHE_LOG_DIR}/error.log\n\
    CustomLog ${APACHE_LOG_DIR}/access.log combined\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html/storage && \
    chmod -R 755 /var/www/html/bootstrap/cache

RUN mkdir -p /var/www/html/storage/framework/{cache,sessions,views,testing} && \
    mkdir -p /var/www/html/storage/logs && \
    chown -R www-data:www-data /var/www/html/storage

HEALTHCHECK --interval=30s --timeout=3s --start-period=40s --retries=3 \
    CMD curl -f http://localhost/api/health || exit 1

EXPOSE 80

CMD ["bash", "-c", "php-fpm -D && apache2ctl -D FOREGROUND"]