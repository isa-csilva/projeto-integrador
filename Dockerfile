FROM php:8.4-apache

RUN docker-php-ext-install pdo_mysql \
    && a2enmod rewrite \
    && cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

WORKDIR /var/www/html
COPY app/ app/
COPY config/ config/
COPY core/ core/
COPY database/ database/
COPY routes/ routes/
COPY public/ public/
COPY deploy/apache.conf /etc/apache2/sites-available/000-default.conf
COPY deploy/php.ini /usr/local/etc/php/conf.d/app.ini

EXPOSE 80
CMD ["apache2-foreground"]
