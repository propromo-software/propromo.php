FROM webdevops/php-nginx:8.3-alpine

COPY nginx.conf /opt/docker/etc/nginx/conf.d
# COPY php.ini /opt/docker/etc/php/php.ini

# Install required packages
RUN apk --no-cache add \
    curl \
    libxml2-dev \
    openssl-dev

# Install and enable required PHP extensions
# RUN docker-php-ext-enable \
#    ctype \
#    curl \
#    dom \
#    fileinfo \
#    filter \
#    hash \
#    mbstring \
#    openssl \
#    pcre \
#    pdo \
#    session \
#    tokenizer \
#    xml

# Copy Composer binary from the Composer official Docker image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Environment variables
ENV WEB_DOCUMENT_ROOT /app/public
ENV APP_ENV production
WORKDIR /app


# Install Node.js and NPM
RUN apk add --no-cache npm

# Install Laravel framework system requirements (https://laravel.com/docs/10.x/deployment)
COPY .env.docker.example .env
COPY . .

# Build assets
RUN npm ci
RUN npm run build

# Install PHP dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Optimizing Configuration loading
RUN php artisan config:cache

# Optimizing Event loading
RUN php artisan event:cache

# Optimizing Route loading
# RUN php artisan route:cache

# Optimizing View loading
RUN php artisan view:cache

RUN chown -R application:application .

EXPOSE 80
