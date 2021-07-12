# sciencemesh-nextcloud
Connect your Nextcloud server to Sciencemesh

This repository contains:

* `nc/`, a Docker image for Nextcloud that uses Nextcloud with [Yvo's app](https://github.com/ylebre/sciencemesh-nextcloud)
* `revad/`, a Docker image for `revad` that uses [Reva with Michiel's storage driver](https://github.com/michielbdejong/reva/tree/nextcloud-storage-driver)
* `revadBase/`, a helper Dockerfile to make rebuilds go faster
* `build.sh` a shell script that builds Nextcloud + Reva Docker images
* `reset.sh` a very destructive shell script that annihilates all your Dockerz
* `run-testnet.sh` a shell script that runs Nextcloud + Reva in a local Docker testnet

# How to use
Set dockerhost and revadhost in your /etc/hosts to a server that runs docker and to which you have root ssh access (in my case I use `64.227.66.5 dockerhost` and `64.227.66.5 revadhost`, you can use `127.0.0.1 dockerhost` and `127.0.0.1 revadhost`), then:

```sh
export DOCKERHOST=ssh://root@dockerhost
./build.sh
./reset.sh
./run-testnet.sh
./connect.sh
reva-cli v1.7.0-91-gbd7d6dc9 (rev-bd7d6dc9)
Please use `exit` or `Ctrl-D` to exit this program.
>> login basic
username: einstein
password: OK # relativity
>> upload --protocol=simple /etc/revad/revad.toml /home/some-file.txt
Local file size: 3607 bytes
Data server: http://127.0.0.1:19001/data/simple/test.txt
Allowed checksums: [type:RESOURCE_CHECKSUM_TYPE_MD5 priority:100  type:RESOURCE_CHECKSUM_TYPE_UNSET priority:1000 ]
Checksum selected: RESOURCE_CHECKSUM_TYPE_MD5
Local XS: RESOURCE_CHECKSUM_TYPE_MD5:73b039816fdbbeca7faaf626c2fa3e01
File uploaded: 123e4567-e89b-12d3-a456-426655440000:fileid-some-file.txt 0 /home/some-file.txt
>> quit
/go # exit
$ docker logs revad
[...]
$ docker logs nc
[...]
```

And then you can see the file was uploaded via the revad container to the nc container:
```sh
$ docker exec nc ls -la data/alice/files/sciencemesh
total 12
drwxr-xr-x 2 www-data www-data 4096 Jun 22 20:25 .
drwxr-xr-x 6 www-data www-data 4096 Jun 22 20:25 ..
-rw-r--r-- 1 www-data www-data 3607 Jun 22 20:25 test
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