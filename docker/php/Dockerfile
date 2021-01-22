FROM php:8.0-fpm-buster

RUN apt update && apt install -y

RUN apt update && apt install -y --no-install-recommends \
		git \
		zlib1g-dev \
		libxml2-dev \
		libzip-dev \
	&& docker-php-ext-install \
		zip \
		intl \
		mysqli \
		pdo pdo_mysql

EXPOSE 9000

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
WORKDIR /var/www/typewriter/
