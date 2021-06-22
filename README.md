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
Set dockerhost and revadhost in your /etc/hosts to a server that runs docker and to which you have root ssh access (in my case I use `64.227.66.5 dockerhost` and `64.227.66.5 revadhost`, you can use `127.0.0.1 dockerhost` and `127.0.0.1 revadhost`), then:

```sh
export DOCKERHOST=ssh://root@dockerhost
./build.sh
./reset.sh
# ./nc-live.sh
# ./revad-live.sh
./run-testnet.sh
```

* When running nc-live.sh, visit http://dockerhost/ and log in as `alice` / `alice123`
* When running revad-live.sh, connect with reva cli from your laptop, for instance `~/gh/cs3org/reva/cmd/reva/reva -insecure -host dockerhost:19000`
* When runnig run-testnet.sh, connect via reva cli from inside the revad container:
```sh
docker exec -it revad /bin/sh
```

```sh
$ ~/gh/cs3org/reva/cmd/reva/reva -insecure -host dockerhost:19000
reva-cli v1.7.0-56-g5b7309bc (rev-5b7309bc)
Please use `exit` or `Ctrl-D` to exit this program.
>> login basic
username: einstein
password: OK
>> ls /home
MyShares
>> upload --protocol=simple README.md /home/test.txt
[...]
>> quit
```
And then you can see the file was uploaded to localfs inside the revad docker container:
```sh
docker exec revad_live ls /var/tmp/reva/data/einstein
```


# Develop reva without Docker
In your checkout of gh:cs3org/reva, run:
```sh
sudo vim /etc/hosts # add: 127.0.0.1 revadhost
git clone https://github.com/michielbdejong/reva
cd reva
git checkout nextcloud-storage-driver
make build-revad
sudo mkdir -p /var/tmp/reva/data/einstein
sudo mkdir -p /etc/revad
sudo cp ../../pondersource/sciencemesh-nextcloud/revad/providers.json /etc/revad/
sudo cp ../../pondersource/sciencemesh-nextcloud/revad/users.json /etc/revad/
./cmd/revad/revad -c ../../pondersource/sciencemesh-nextcloud/revad/revad.toml
```
And then use `~/gh/cs3org/reva/cmd/reva/reva -insecure -host localhost:19000` to connect.