FROM php:8.2-fpm

# Install system packages
RUN apt-get update && apt-get install -y nginx supervisor libpq-dev zip unzip && docker-php-ext-install pdo pdo_mysql

# Copy PHP source code
COPY app/ /var/www/html/

# Copy nginx config
COPY nginx/default.conf /etc/nginx/conf.d/default.conf

# Supervisor to manage Nginx + PHP-FPM
COPY php/supervisord.conf /etc/supervisord.conf

# Expose port 80
EXPOSE 80

# Start supervisord
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
