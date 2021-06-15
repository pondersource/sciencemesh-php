#!/bin/bash

# rm -rf docker/nc/nc-sciencemesh
# cp -r nc-sciencemesh docker/nc/
# docker build -t nc docker/nc/

rm -rf docker/revad/reva-storage-nextcloud
cp -r reva-storage-nextcloud docker/revad/
docker build -t revad docker/revad/
