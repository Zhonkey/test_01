FROM yiisoftware/yii2-php:8.3-fpm-nginx

RUN apt-get update && apt-get install -y \
    libmemcached-dev zlib1g-dev gettext-base \
    && pecl install memcached \
    && docker-php-ext-enable memcached

ENTRYPOINT ["php", "./yii"]