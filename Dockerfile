FROM php:8.3-apache

RUN docker-php-ext-install mysqli

RUN a2dismod mpm_event && a2enmod mpm_prefork

COPY . /var/www/html/

EXPOSE 8080

CMD ["bash", "-c", "sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf && sed -i 's/:80>/:8080>/' /etc/apache2/sites-enabled/000-default.conf && apache2-foreground"]