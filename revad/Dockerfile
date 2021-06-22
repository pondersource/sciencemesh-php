FROM golang:alpine as builder
RUN apk --no-cache add \
  ca-certificates \
  bash \
  git \
  gcc \
  libc-dev \
  make

ENV PATH /go/bin:/usr/local/go/bin:$PATH
ENV GOPATH /go

WORKDIR /go/src/github/cs3org
RUN git clone https://github.com/michielbdejong/reva
WORKDIR /go/src/github/cs3org/reva
RUN git checkout nextcloud-storage-driver

RUN make build-revad-docker && \
    cp /go/src/github/cs3org/reva/cmd/revad/revad /go/bin/revad

RUN mkdir -p /etc/revad/ && echo "" > /etc/revad/revad.toml

FROM golang:alpine

RUN apk --no-cache add \
  mailcap

EXPOSE 9999 10000

COPY --from=builder /etc/ssl/certs/ca-certificates.crt /etc/ssl/certs/
COPY --from=builder /go/bin/revad /usr/bin/revad
COPY --from=builder /etc/revad /etc/revad

ADD revad.toml /etc/revad/revad.toml
ADD users.json /etc/revad/users.json
ADD providers.json /etc/revad/providers.json
# the "local" storage driver will use /var/tmp/reva/data/
# the "localhome" storage driver will use /var/tmp/reva/data/einstein/
RUN mkdir -p /var/tmp/reva/data/einstein/

ENTRYPOINT [ "/usr/bin/revad" ]
CMD [ "-c", "/etc/revad/revad.toml", "-p", "/var/run/revad.pid" ]
