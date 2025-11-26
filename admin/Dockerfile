FROM yiisoftware/yii2-php:8.4-apache

RUN apt-get update && apt-get install -y \
    libmemcached-dev zlib1g-dev \
    && pecl install memcache \
    && docker-php-ext-enable memcache

# Change document root for Apache
RUN sed -i -e 's|/app/web|/app/admin/web|g' /etc/apache2/sites-available/000-default.conf
