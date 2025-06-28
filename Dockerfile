# Dockerfile
FROM php:8.1-alpine

# Install git and p7zip
RUN apk add --no-cache git p7zip bash

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony*/bin/symfony /usr/local/bin/symfony
WORKDIR /var/www
RUN composer install

RUN chown -R www-data:www-data /var/www

COPY . .

CMD ["symfony", "server:start", "0.0.0.0:8000"]