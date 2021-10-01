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
#RUN git clone https://github.com/ylebre/sciencemesh-nextcloud /usr/src/nextcloud/apps/sciencemesh
#WORKDIR /usr/src/nextcloud/apps/sciencemesh
# RUN make
# RUN php /install/composer.phar require symfony/orm-pack
# RUN php /install/composer.phar update
# RUN php /install/composer.phar install --no-dev --prefer-dist
#WORKDIR /var/www/html
#ADD init.sh /
#EXPOSE 443

RUN apt-get -y install cmake
RUN apt-get -y install unzip
RUN apt-get -y install libghc-zlib-dev
RUN pear config-set php_ini /usr/local/etc/php/conf.d/nextcloud.ini
RUN pecl install grpc
RUN pecl install protobuf
RUN mkdir /opt/grpc-build
WORKDIR /opt/grpc-build
RUN git clone --recurse-submodules -b v1.40.0 https://github.com/grpc/grpc
RUN curl -O -L https://github.com/protocolbuffers/protobuf/releases/download/v3.18.0/protoc-3.18.0-linux-x86_64.zip
RUN unzip protoc-3.18.0-linux-x86_64.zip
RUN cp bin/protoc /usr/local/bin
RUN cp -a include/* /usr/local/include
RUN chmod +x /usr/local/bin/protoc
WORKDIR /opt/grpc-build/grpc/cmake
RUN mkdir build
WORKDIR /opt/grpc-build/grpc/cmake/build
RUN cmake ../../
RUN make protoc grpc_php_plugin
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php
RUN rm composer-setup.php
RUN mv composer.phar /usr/local/bin/composer
