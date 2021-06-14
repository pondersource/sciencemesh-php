#!/bin/bash
set -e

docker run --network=host -d --name nc_live nc
until curl -I http://dockerbak 2> /dev/null > /dev/null
  do
    echo Waiting for Nextcloud to start on dockerbak, this can take up to a minute ...
    docker ps -a
    docker logs nc_live
    sleep 1
  done
docker exec -u www-data -it -e SERVER_ROOT=http://dockerbak nc_live sh /init.sh
docker exec -u root -it nc_live service apache2 reload
echo vim config/config.php +24
docker exec -u www-data -it nc_live /bin/bash

e