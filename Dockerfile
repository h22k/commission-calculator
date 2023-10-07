FROM php:8.2-fpm-alpine

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /usr/commission-calculator
COPY . /usr/commission-calculator

RUN composer install

CMD ["php", "app.php", "input.txt"]

