FROM php:8.1.1-cli

RUN apt-get update

# PHP config
RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini
RUN sed -i 's/memory_limit = 128M/memory_limit = 2G/' /usr/local/etc/php/php.ini

# Composer install
ENV COMPOSER_HOME /var/www/.composer/
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer
RUN chmod +x /usr/local/bin/composer

RUN mkdir -p /home/prettycsv
WORKDIR /home/prettycsv

ENTRYPOINT ["tail", "-f", "/dev/null"]