#!/bin/bash
set -e

docker run --network=host -d --name nc_live nc
until curl -I http://dockerhost 2> /dev/null > /dev/null
  do
    echo Waiting for Nextcloud to start on dockerhost, this can take up to a minute ...
    docker ps -a
    docker logs nc_live
    sleep 1
  done
docker exec -u www-data -it -e SERVER_ROOT=http://dockerhost nc_live sh /init.sh
docker exec -u www-data -it nc_live sed -i '25 a\ \ \ \ 1 => "dockerhost",' config/config.php
docker exec -u root -it nc_live service apache2 reload
