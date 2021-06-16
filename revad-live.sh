#!/bin/bash
set -e
docker build -t revad ./revad/
docker stop revad_live
docker rm revad_live
docker run --network=host -d --name revad_live revad
docker logs -f revad_live
