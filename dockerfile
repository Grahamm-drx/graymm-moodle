FROM php:8.0-apache

# Install system packages and required PHP extensions for Moodle
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libicu-dev \
    libxml2-dev \
    libzip-dev \
    libonig-dev \
    libcurl4-openssl-dev \
    libssl-dev \
    gettext \
    git \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        mysqli \
        pdo_mysql \
        gd \
        intl \
        xml \
        zip \
        soap \
        mbstring \
        curl \
        opcache \
        gettext

# Enable the Apache Rewrite module
RUN a2enmod rewrite

# Configure Apache to listen on Render's dynamic PORT environment variable
RUN sed -i 's/80/${PORT}/g' /etc/apache2/ports.conf /etc/apache2/sites-available/000-default.conf

# Copy the local source code into the web root
COPY . /var/www/html/

# Create moodledata and set correct permissions for the web server user
RUN mkdir -p /var/moodledata && chown -R www-data:www-data /var/moodledata /var/www/html

# Increase max_input_vars to meet Moodle requirements
RUN echo "max_input_vars = 5000" > /usr/local/etc/php/conf.d/moodle-custom.ini

EXPOSE 80
CMD ["apache2-foreground"]