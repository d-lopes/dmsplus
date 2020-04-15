FROM php:7.3-fpm

LABEL maintainer="tech@dlopes.de"

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    libzip-dev \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    netcat
    
# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl
RUN docker-php-ext-configure gd --with-gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/
RUN docker-php-ext-install gd

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Set working directory
WORKDIR /var/www

# Copy existing application directory
COPY --chown=www:www . /var/www
    
# install dependencies of laravel application
RUN composer install

# setup file structure for webapp to work
RUN mkdir -p /var/www/storage/logs && \
    mkdir -p /var/www/storage/search && \
    cd public && ln -s /var/www/storage/app/documents files

# set application directory permissions
RUN chmod 775 -R /var/www/storage
  
# tweak php configuration
RUN mv config/php/local.ini /usr/local/etc/php/conf.d

# add enrypoint script and make it executable
RUN mv docker-entrypoint.sh /var/run && \
    chmod 744 /var/run/docker-entrypoint.sh

# Change current user to www
USER www

# expose web folder so that it can be shared with nginx server
VOLUME [ "/var/www", "/var/www/public/files", "/var/www/storage/search", "/var/www/storage/logs" ]

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]

# run entrypoint script
ENTRYPOINT ["/var/run/docker-entrypoint.sh"]