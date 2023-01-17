FROM alpine:3.15

ARG uid=1000
ARG gid=1000

RUN adduser -D -u $uid -g $gid -s /bin/sh www && \
    mkdir -p /var/www/html && \
    chown -R www:www /var/www/html

RUN apk --no-cache add nginx \ 
        ca-certificates \
        # openrc \    
        git \
        curl \
        openssh \
        sqlite \
        supervisor \
        php7 \
        php7-fpm \
        php7-apcu \
        php7-bcmath \
        php7-bz2 \
        php7-cgi \
        php7-ctype \
        php7-curl \
        php7-dom \
        php7-ftp \
        php7-gd \
        php7-iconv \
        php7-json \
        php7-mbstring \
        php7-pecl-oauth \
        php7-opcache \
        php7-openssl \
        php7-pcntl \
        php7-fileinfo \
        php7-pecl-msgpack \
        php7-pdo \
        php7-pdo_mysql \
        php7-phar \
        php7-sqlite3 \
        php7-pdo_sqlite \
        php7-redis \
        php7-pecl-imagick \
        php7-session \
        php7-simplexml \
        php7-tokenizer \
        php7-xmlreader \
        php7-exif \
        php7-xdebug \
        php7-xml \
        php7-xmlwriter \
        php7-zip \
        php7-zlib --repository http://nl.alpinelinux.org/alpine/edge/testing/ 

RUN ln -s -f /usr/bin/php7 /usr/bin/php
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer 
RUN rm -rf /var/cache/apk/*

# Configure PHP-FPM
COPY docker-config/fpm-pool.conf /etc/php7/php-fpm.d/www.conf

# Configure supervisord
COPY docker-config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Setup document root
RUN mkdir -p /var/www/html

# Make sure files/folders needed by the processes are accessable when they run under the www user
RUN chown -R www.www /run && \
  chown -R www.www /var/lib/nginx && \
  chown -R www.www /var/log/nginx

# Switch to use a non-root user from here on
USER www

# Add application
WORKDIR /var/www/html
# COPY --chown=www src/ /var/www/html/

# Expose the port nginx is reachable on
EXPOSE 8080
# EXPOSE 8000 -> if you want to use artisan serve

# Let supervisord start nginx & php-fpm
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
# CMD ["php artisan serve"] -> if you want to use artisan serve
# Configure a healthcheck to validate that everything is up&running
HEALTHCHECK --timeout=10s CMD curl --silent --fail http://127.0.0.1:8080/fpm-ping