FROM php:8.1-cli

RUN apt-get update \
    && apt-get install -y --no-install-recommends libfreetype6-dev libjpeg62-turbo-dev libpng-dev libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd mbstring mysqli pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /app
COPY . /app

RUN mkdir -p application/data application/cache/sessions \
    && chmod -R 775 application/data application/cache

ENV CI_ENV=production
ENV HTTPS=on
EXPOSE 8080

CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-8080} router.php"]
