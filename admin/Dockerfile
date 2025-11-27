FROM yiisoftware/yii2-php:8.1-fpm-nginx

RUN apt-get update && apt-get install -y \
    libmemcached-dev zlib1g-dev \
    && pecl install memcache \
    && docker-php-ext-enable memcache

# Change document root for Apache
RUN sed -i -e 's|/app/web|/app/admin/web|g' /etc/nginx/conf.d/default.conf
