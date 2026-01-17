FROM php:8.4-fpm

# --------------------------------------------------
# Config files
# --------------------------------------------------
COPY php.ini /usr/local/etc/php/
COPY docker.conf /usr/local/etc/php-fpm.d/docker.conf
COPY .bashrc /root/

# Entrypoint
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# --------------------------------------------------
# System dependencies + PHP extensions
# --------------------------------------------------
RUN apt-get update && apt-get install -y \
    build-essential \
    zlib1g-dev \
    default-mysql-client \
    curl \
    gnupg \
    procps \
    vim \
    git \
    unzip \
    libzip-dev \
    libpq-dev \
    libicu-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    && docker-php-ext-install \
        zip \
        pdo_mysql \
        pdo_pgsql \
        pgsql \
        pcntl \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && docker-php-ext-configure gd \
        --with-freetype=/usr/include/ \
        --with-jpeg=/usr/include/ \
    && docker-php-ext-install gd \
    && pecl install redis pcov \
    && docker-php-ext-enable redis pcov \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# --------------------------------------------------
# Node.js (LTS) + Yarn
# --------------------------------------------------
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g yarn

# --------------------------------------------------
# Composer
# --------------------------------------------------
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/local/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_HOME=/composer
ENV PATH="$PATH:/composer/vendor/bin"

RUN composer config --global process-timeout 3600 \
    && composer global require laravel/installer

# --------------------------------------------------
# Extras
# --------------------------------------------------
WORKDIR /root
RUN git clone https://github.com/seebi/dircolors-solarized

# --------------------------------------------------
# App
# --------------------------------------------------
WORKDIR /var/www

ENTRYPOINT ["entrypoint.sh"]
CMD ["php-fpm"]
