# sciencemesh-nextcloud
Connect your Nextcloud server to Sciencemesh

This repository contains:

* `nc-sciencemesh/`, a plugin for Nextcloud
* `docker/nextcloud/`, a Docker image for Nextcloud that uses `nc-sciencemesh`
* `reva-storage-nextcloud/`, a plugin for Reva (storage backend for `revad`)
* `docker/revad/`, a Docker image for `revad` that uses `reva-storage-nextcloud`
* `run.sh` a shell script that builds and runs Nextcloud + Reva in a local Docker testnet
