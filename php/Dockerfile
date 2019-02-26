FROM php:7.0-fpm
RUN DEBIAN_FRONTEND=noninteractive apt-get update && apt-get install -y apt-utils
RUN DEBIAN_FRONTEND=noninteractive apt-get install -y \
        libfreetype6-dev \
        libmcrypt-dev \
        libpng-dev \
        libcurl4-nss-dev \
        libxml2-dev \
        libxslt-dev \
        apt-utils \
        libjpeg-dev \
        libpq-dev \
        libmemcached-dev \
        curl
RUN docker-php-ext-install iconv \
    && docker-php-ext-install gd \
    && docker-php-ext-install curl \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install pdo \
    && docker-php-ext-install mbstring \
    && docker-php-ext-install json \
    && docker-php-ext-install soap \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install mcrypt \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install xsl \
    && docker-php-ext-install intl \
    && docker-php-ext-install zip \
    && docker-php-ext-install bcmath
RUN DEBIAN_FRONTEND=noninteractive apt-get update 
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/lib/x86_64-linux-gnu --with-jpeg-dir=/usr/lib/x86_64-linux-gnu && docker-php-ext-install gd
