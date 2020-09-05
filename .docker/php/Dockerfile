FROM php:7.4-fpm

# Utilities
RUN apt-get update \
    && apt-get install -y --no-install-recommends debconf npm git unzip \
    wget sudo acl

# PHP Extensions
RUN apt-get update && apt-get install -y \
        libpq-dev libonig-dev libxslt-dev \
    && docker-php-ext-install \
        pdo pdo_pgsql pgsql \
        mbstring \
        json \
        xsl \
        opcache

#RUN apt-get install -y \
#        libzip-dev \
#        zip \
#  && docker-php-ext-configure zip --with-libzip \
#  && docker-php-ext-install zip

RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

# Redis Extension
RUN pecl install -o -f redis \
  &&  rm -rf /tmp/pear \
  &&  docker-php-ext-enable redis

# Copy config files
COPY php.ini /usr/local/etc/php/php.ini
COPY php-fpm-pool.conf 	/usr/local/etc/php/pool.d/www.conf

# Download symfony as global
RUN wget https://get.symfony.com/cli/installer -O - | bash
RUN mv /root/.symfony/bin/symfony /usr/local/bin/symfony

# Download composer as global
RUN curl -sSk https://getcomposer.org/installer | php -- --disable-tls && \
	mv composer.phar /usr/local/bin/composer

# Crean grupos de trabajo
RUN groupadd dev -g 999
RUN useradd dev -g dev -d /home/dev -m
RUN passwd -d dev

WORKDIR /usr/src/app

RUN setfacl -R -m u:www-data:rwx -m u:`whoami`:rwx /../../var
RUN setfacl -dR -m u:www-data:rwx -m u:`whoami`:rwx /../../var

EXPOSE 9000

CMD ["php-fpm"]
