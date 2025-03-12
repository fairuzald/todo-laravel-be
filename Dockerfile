FROM php:8.2-fpm

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
  git \
  curl \
  libpng-dev \
  libonig-dev \
  libxml2-dev \
  zip \
  unzip \
  && apt-get clean \
  && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN useradd -G www-data,root -u 1000 -d /home/devuser devuser
RUN mkdir -p /home/devuser/.composer && \
  chown -R devuser:devuser /home/devuser

RUN mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache && \
  chown -R www-data:www-data /var/www/html && \
  chmod -R 775 /var/www/html

COPY --chown=devuser:devuser composer.json composer.lock ./

USER devuser
RUN composer install --no-interaction --no-plugins --no-scripts --no-autoloader

COPY --chown=www-data:www-data . .

RUN composer dump-autoload --optimize

USER root

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && \
  chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

RUN echo '#!/bin/sh\n\
  set -e\n\
  \n\
  chown -R www-data:www-data /var/www/html/storage\n\
  chown -R www-data:www-data /var/www/html/bootstrap/cache\n\
  chmod -R 775 /var/www/html/storage\n\
  chmod -R 775 /var/www/html/bootstrap/cache\n\
  \n\
  php artisan config:clear\n\
  php artisan view:clear\n\
  php artisan route:clear\n\
  php artisan cache:clear\n\
  \n\
  mkdir -p /var/www/html/storage/api-docs\n\
  chmod -R 775 /var/www/html/storage/api-docs\n\
  php artisan l5-swagger:generate\n\
  \n\
  exec php-fpm\n\
  ' > /usr/local/bin/entrypoint.sh && \
  chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 9000
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
