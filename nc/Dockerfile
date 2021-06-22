FROM nextcloud:latest
COPY site.conf /etc/apache2/sites-enabled/000-default.conf
RUN a2enmod ssl
RUN mkdir /tls
RUN openssl req -new -x509 -days 365 -nodes \
  -out /tls/server.cert \
  -keyout /tls/server.key \
  -subj "/C=RO/ST=Bucharest/L=Bucharest/O=IT/CN=www.example.ro"
RUN apt-get update && apt-get install -yq \
  certbot \
  git \
  python3-certbot-apache \
  vim
# WORKDIR /install
# RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
# RUN php composer-setup.php
# RUN php -r "unlink('composer-setup.php');"
RUN git clone https://github.com/ylebre/sciencemesh-nextcloud /usr/src/nextcloud/apps/sciencemesh
WORKDIR /usr/src/nextcloud/apps/sciencemesh
RUN make
# RUN php /install/composer.phar require symfony/orm-pack
# RUN php /install/composer.phar update
# RUN php /install/composer.phar install --no-dev --prefer-dist
WORKDIR /var/www/html
ADD init.sh /
EXPOSE 443
