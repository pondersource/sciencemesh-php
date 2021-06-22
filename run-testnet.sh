#!/bin/bash
set -e

docker network create testnet
docker run --network=testnet -d --name nc nc
until docker run --rm --network=testnet nc curl -kI http://nc 2> /dev/null > /dev/null
  do
    echo Waiting for Nextcloud to start on dockerhost, this can take up to a minute ...
    docker ps -a
    docker logs nc
    sleep 1
  done
docker exec -u www-data -it -e SERVER_ROOT=http://nc nc sh /init.sh
docker exec -u www-data -it nc sed -i '25 a\ \ \ \ 1 => "nc",' config/config.php
docker exec -u root -it nc service apache2 reload
echo nc is up, now starting revad:
docker run --network=testnet -d --name revad revad
docker ps -a