FROM alpine:3.10

RUN apk --update --no-cache add \
    bash \
    sudo \
    curl \
    git \
    unzip \
    nginx \
    php7-fpm \
    php7-opcache \
    php7-mbstring \
    php7-json \
    php7-intl \
    php7-curl \
    composer

RUN set -x ; \
  addgroup -g 82 -S www-data ; \
  adduser -u 82 -D -S -G www-data www-data && exit 0 ; exit 1

ADD docker/nginx.conf /etc/nginx/nginx.conf
ADD docker/php-fpm.conf /etc/php7/php-fpm.conf

WORKDIR /app

RUN mkdir -p /run/nginx/ && \
    chown www-data /app && \
    chgrp www-data /app && \
    echo "www-data ALL=(ALL) NOPASSWD: /usr/sbin/nginx" >> /etc/sudoers

EXPOSE 80

USER www-data

COPY --chown=www-data:www-data . /app/

CMD php-fpm7 -D && sudo nginx
