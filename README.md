# sciencemesh-nextcloud
Connect your Nextcloud server to Sciencemesh

This repository contains:

* `nc-sciencemesh/`, a plugin for Nextcloud
* `docker/nextcloud/`, a Docker image for Nextcloud that uses `nc-sciencemesh`
* `reva-storage-nextcloud/`, a plugin for Reva (storage backend for `revad`)
* `docker/revad/`, a Docker image for `revad` that uses `reva-storage-nextcloud`
* `build.sh` a shell script that builds Nextcloud + Reva Docker images
* `run.sh` a shell script that runs Nextcloud + Reva in a local Docker testnet

# How to use
Set dockerbak in your /etc/hosts to a server that runs docker (in my case I use `64.227.66.5 dockerbak`, you can use `127.0.0.1 dockerbak`), then:

```sh
export DOCKERHOST=ssh://root@dockerbak
./build.sh
./reset.sh
./nc-live.sh
docker logs nc_live
./revad-live.sh
```

* Visit http://dockerbak/ and log in as `alice` / `alice123`
* Connect with reva cli, for instance `~/gh/cs3org/reva/cmd/reva/reva -insecure -host dockerbak:18000`
```sh
$ login basic
username: einstein
password: relativity
$ ls /home
```