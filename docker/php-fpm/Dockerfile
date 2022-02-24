FROM php:8.1.2-fpm
WORKDIR "/app"

RUN apt-get update
RUN apt-get upgrade -y

RUN apt-get update && apt-get install apt-file -y && apt-file update

RUN apt-get -y install -qq openssh-client wget curl git
RUN apt-get -y install -qq  libxml2-dev libbz2-dev zlib1g-dev

RUN apt-get install -y libzip-dev zip
RUN docker-php-ext-install zip
RUN docker-php-ext-install pdo pdo_mysql mysqli

RUN docker-php-ext-install xml
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install zip
RUN docker-php-ext-install intl

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN pecl install xdebug && docker-php-ext-enable xdebug
RUN apt-get clean
RUN rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*
