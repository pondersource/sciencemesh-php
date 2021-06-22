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

### FIXME:
`revad.toml` currently has the data server url as http://127.0.0.1:19001/data
This works for the run-testnet.sh instructions, but for the revad-live.sh instructions
you need to set it to http://revadhost:19001/data or http://dockerhost:19001/data.

* When running nc-live.sh, visit http://dockerhost/ and log in as `alice` / `alice123`
* When running revad-live.sh, connect with reva cli from your laptop, for instance `~/gh/cs3org/reva/cmd/reva/reva -insecure -host dockerhost:19000`
* When runnig run-testnet.sh, connect via reva cli from inside the revad container:
```sh
$ docker exec -it revad /bin/sh
/go # /usr/bin/reva -insecure -host localhost:19000
reva-cli v1.7.0-91-gbd7d6dc9 (rev-bd7d6dc9)
Please use `exit` or `Ctrl-D` to exit this program.
>> login basic
username: einstein
password: OK # relativity
>> upload --protocol=simple /etc/revad/revad.toml /home/test.txt
Local file size: 3607 bytes
Data server: http://127.0.0.1:19001/data/simple/test.txt
Allowed checksums: [type:RESOURCE_CHECKSUM_TYPE_MD5 priority:100  type:RESOURCE_CHECKSUM_TYPE_UNSET priority:1000 ]
Checksum selected: RESOURCE_CHECKSUM_TYPE_MD5
Local XS: RESOURCE_CHECKSUM_TYPE_MD5:73b039816fdbbeca7faaf626c2fa3e01
Put "http://127.0.0.1:19001/data/simple/test.txt?xs=73b039816fdbbeca7faaf626c2fa3e01&xs_type=md5": EOF
>> quit
/go # exit
$ docker logs revad
[...]
$ docker logs nc
[...]
172.22.0.3 - - [22/Jun/2021:20:18:03 +0000] "PUT /apps/sciencemesh/test HTTP/1.1" 302 1489 "-" "Go-http-client/1.1"
172.22.0.3 - - [22/Jun/2021:20:18:04 +0000] "GET /login HTTP/1.1" 200 5092 "http://nc/apps/sciencemesh/test" "Go-http-client/1.1"
```

And then you can see the file was uploaded via the revad container to the nc container:
```sh
docker exec nc ls [?]
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