FROM php:7.4-fpm-alpine

# Locate the application
WORKDIR /var/www

# Instal php depedencies
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions mysqli

CMD ["php-fpm"]
