FROM php:7.2.5-apache

RUN docker-php-ext-install pdo pdo_mysql

# Enable url rewriting
# RUN a2enmod rewrite
# RUN service apache2 restart

COPY . /web

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]

# Reference list:
# https://perchrunway.com/blog/2017-01-19-getting-started-with-docker-for-local-development
# https://stackoverflow.com/a/48884686/9636402
