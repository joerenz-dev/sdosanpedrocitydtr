FROM php:8.3-apache

RUN docker-php-ext-install mysqli

# Disable all MPMs except prefork (required for mod_php)
RUN a2dismod -f mpm_event mpm_worker 2>/dev/null; a2enmod mpm_prefork && \
    rm -f /etc/apache2/mods-enabled/mpm_event.load /etc/apache2/mods-enabled/mpm_worker.load

COPY . /var/www/html/

EXPOSE 8080

CMD ["bash", "-c", "rm -f /etc/apache2/mods-enabled/mpm_event.* /etc/apache2/mods-enabled/mpm_worker.* && sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf && sed -i 's/:80>/:8080>/' /etc/apache2/sites-enabled/000-default.conf && apache2-foreground"]