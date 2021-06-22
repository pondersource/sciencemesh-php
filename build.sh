#!/bin/bash

docker build -t nc nc/
docker build -t revad-base revadBase/
docker build -t revad --no-cache revad/
