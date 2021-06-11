#!/bin/bash

rm -rf docker/nc/nc-sciencemesh
cp -r nc-sciencemesh docker/nc/
docker build -t nc docker/nc/
docker build -t revad docker/revad/
