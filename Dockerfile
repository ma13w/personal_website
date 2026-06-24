FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
        git \
        zip \
        unzip \
        libzip-dev \
    && docker-php-ext-install zip \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer

# Enable Apache modules
RUN a2enmod rewrite headers

# Harden PHP
RUN echo 'expose_php = Off' > /usr/local/etc/php/conf.d/security.ini

# Fix ports.conf explicitly
RUN echo 'Listen 80' > /etc/apache2/ports.conf

WORKDIR /var/www/html

# Run composer install at build time if composer.json exists
COPY . .
RUN if [ -f composer.json ]; then composer install --no-dev --optimize-autoloader; fi

EXPOSE 80
