FROM richarvey/nginx-php-fpm:1.9.1

COPY . .

# Image config
ENV SKIP_COMPOSER 1
ENV WEBROOT ./public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Laravel config
ENV APP_ENV local
ENV APP_DEBUG true
ENV LOG_CHANNEL stderr

# Allow composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER 1

CMD ["/start.sh"]