FROM php:7.4-fpm

ARG user=alice
ARG uid=1000
ENV user ${user}

RUN apt-get update && apt-get install -y \
    curl \
    build-essential \
    git \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure intl && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install intl gd pdo_mysql mbstring exif pcntl bcmath zip && \
    pecl install redis && docker-php-ext-enable redis

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Copy repository files into container and give permissions
COPY . /var/www/html
RUN chown -R $user:$user /var/www/html

WORKDIR /var/www/html

USER $user

EXPOSE 9000
