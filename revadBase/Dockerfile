FROM golang:alpine
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
# RUN git checkout upload-working
RUN git checkout nextcloud-integration

RUN make build-revad-docker && \
    cp /go/src/github/cs3org/reva/cmd/revad/revad /go/bin/revad
RUN make build-reva-docker && \
    cp /go/src/github/cs3org/reva/cmd/reva/reva /go/bin/reva

RUN mkdir -p /etc/revad/ && echo "" > /etc/revad/revad.toml
