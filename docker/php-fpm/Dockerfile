FROM php:8.2-fpm

RUN echo "$(curl -sS https://composer.github.io/installer.sig) -" > composer-setup.php.sig \
        && curl -sS https://getcomposer.org/installer | tee composer-setup.php | sha384sum -c composer-setup.php.sig \
        && php composer-setup.php && rm composer-setup.php* \
        && chmod +x composer.phar && mv composer.phar /usr/bin/composer


RUN apt update && apt install -y zlib1g-dev g++ git libicu-dev zip libzip-dev zip

RUN docker-php-ext-install intl opcache pdo pdo_mysql
RUN docker-php-ext-configure zip
RUN docker-php-ext-install zip

WORKDIR /application
