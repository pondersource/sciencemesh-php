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
RUN git clone https://github.com/ylebre/reva
WORKDIR /go/src/github/cs3org/reva
RUN git checkout static-build

RUN make build-revad-docker && \
    cp /go/src/github/cs3org/reva/cmd/revad/revad /go/bin/revad

RUN mkdir -p /etc/revad/ && echo "" > /etc/revad/revad.toml

FROM ubuntu
RUN apt-get update && apt-get install -yq\
  git \
  vim

EXPOSE 9999 10000

COPY --from=builder /etc/ssl/certs/ca-certificates.crt /etc/ssl/certs/
COPY --from=builder /go/bin/revad /usr/bin/revad
COPY --from=builder /etc/revad /etc/revad

ENTRYPOINT [ "/usr/bin/revad" ]
CMD [ "-c", "/etc/revad/revad.toml", "-p", "/var/run/revad.pid" ]