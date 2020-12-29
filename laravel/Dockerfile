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

RUN curl -sL https://deb.nodesource.com/setup_15.x | bash
RUN apt-get install -y nodejs

# Set working directory
WORKDIR /var/www

# Copy existing application directory and enrypoint script
COPY --chown=www-data:www-data . /var/www
COPY --chown=www-data:www-data docker-entrypoint.sh /var/run 

# tweak php configuration
RUN mv config/php/local.ini /usr/local/etc/php/conf.d

# renew dependencies of laravel application
RUN rm -rf /var/www/vendor && rm -rf /var/www/node_modules
RUN composer install
RUN npm install && npm run prod

# setup file structure for webapp to work
RUN mkdir -p /var/www/storage/logs && \
    mkdir -p /var/www/storage/search && \
    mkdir -p /var/www/storage/app/documents && \
    cd public && ln -s /var/www/storage/app/documents files

# set application's storage directory to R/W permissions
#   and make the enrypoint script executable
RUN chmod 755 -R /var/www/storage && \
    chmod 744 /var/run/docker-entrypoint.sh

# expose web folder so that it can be shared with nginx server
VOLUME [ "/var/www", "/var/www/storage/app/documents", "/var/www/storage/app/uploads", "/var/www/storage/search", "/var/www/storage/logs" ]

# Expose port 9000 and start php-fpm server
EXPOSE 9000

# run entrypoint script
ENTRYPOINT ["/var/run/docker-entrypoint.sh"]