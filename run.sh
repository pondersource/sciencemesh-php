#!/bin/bash
set -e

docker network create testnet
docker run -d --name nc --network=testnet nc
until docker run --rm --network=testnet nc curl -kI https://nc 2> /dev/null > /dev/null
do
  echo Waiting for nc to start, this can take up to a minute ...
  docker ps -a
  docker logs nc
  sleep 1
done
docker logs nc
echo Running init script for Nextcloud...
docker exec -u www-data -it -e SERVER_ROOT=https://nc nc sh /init.sh
docker exec -u root -it nc service apache2 reload

docker run -d --name revad --network=testnet revad

# docker run --network=host -d nc --name nc_live
# docker exec -u www-data -it -e SERVER_ROOT=http://dockerbak nc_live sh /init.sh
# docker exec -u root -it nc_live service apache2 reload
# docker exec -it nc_live vim config/config.php +24

