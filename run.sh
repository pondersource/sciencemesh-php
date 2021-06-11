#!/bin/bash
set -e

function setup {
  docker network create testnet
  # docker build -t nc https://github.com/pdsinterop/solid-nextcloud/
  # docker build -t revad ./revad/
}

function teardown {
  docker stop `docker ps --filter network=testnet -q`
  docker rm `docker ps --filter network=testnet -qa`
  docker network remove testnet
}

function startNextcloud {
  docker run -d --name $1 --network=testnet nc
  until docker run --rm --network=testnet nc curl -kI https://$1 2> /dev/null > /dev/null
  do
    echo Waiting for $1 to start, this can take up to a minute ...
    docker ps -a
    docker logs $1
    sleep 1
  done

  docker logs $1
  echo Running init script for Nextcloud $1 ...
  docker exec -u www-data -it -e SERVER_ROOT=https://$1 $1 sh /init.sh
  docker exec -u root -it $1 service apache2 reload
}

function startRevad {
  docker run -d --name $1 --network=testnet revad
}

# ...
teardown || true
setup
startNextcloud nextcloud
startRevad revad
teardown
