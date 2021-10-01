FROM nextcloud:22

RUN apt-get update && apt-get install -yq \
  certbot \
  cmake \
  git \
  libghc-zlib-dev \
  python3-certbot-apache \
  unzip \
  vim

RUN mkdir /tls && openssl req -new -x509 -days 365 -nodes \
  -out /tls/server.cert \
  -keyout /tls/server.key \
  -subj "/C=RO/ST=Bucharest/L=Bucharest/O=IT/CN=www.example.ro"

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN a2enmod ssl \
    && pear config-set php_ini /usr/local/etc/php/conf.d/nextcloud.ini \
    && pecl install grpc protobuf \
    && mkdir /opt/grpc-build

WORKDIR /opt/grpc-build

RUN git clone --recurse-submodules -b v1.40.0 https://github.com/grpc/grpc /opt/grpc-build/grpc \
    && curl -O -L https://github.com/protocolbuffers/protobuf/releases/download/v3.18.0/protoc-3.18.0-linux-x86_64.zip \
	&& unzip protoc-3.18.0-linux-x86_64.zip \
	&& cp bin/protoc /usr/local/bin \
	&& cp -a include/* /usr/local/include \
	&& chmod +x /usr/local/bin/protoc \
	&& mkdir /opt/grpc-build/grpc/cmake/build

WORKDIR /opt/grpc-build/grpc/cmake/build

RUN cmake ../../ && make protoc grpc_php_plugin