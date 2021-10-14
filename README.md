# sciencemesh-nextcloud
Connect your Nextcloud server to Sciencemesh

This repository contains:

* `nc/`, a Docker image for Nextcloud that uses Nextcloud with [Yvo's app](https://github.com/pondersource/nc-sciencemesh)
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
Make sure you have Nextcloud with https://github.com/pondersource/nc-sciencemesh running on localhost,
for instance by running `./build.sh && ./nc-live.sh` in this repo, or setting Nextcloud up in some
other way and then installing the nc-sciencemesh app manually.

Then, in your checkout of gh:cs3org/reva, run:
```sh
sudo vim /etc/hosts # add: 127.0.0.1 revadhost
git clone https://github.com/michielbdejong/reva
cd reva
git checkout nextcloud-integration
make build-revad
GODEBUG=netdns=go  ./cmd/revad/revad -c examples/nextcloud-integration/revad.toml 
```
And then use `~/gh/cs3org/reva/cmd/reva/reva -insecure -host localhost:19000`
to connect.

# Run tests
You can run the tests of nc-sciencemesh itself as follows:


## nc-sciencemesh tests
* Clone [nextcloud/server](https://github.com/nextcloud/server)
* Install its dependencies, including the one for SQLite, and PHP (e.g. php 7.4)
* Run `php -S localhost:8080`, browse to https://localhost:8080, and make `einstein`/`relativity` the admin user
* Also create a `tester`/`root` user
* Inside the tester's root folder(data/tester/files/sciencemesh), create some/path/test.json
* Clone [nc-sciencemesh]((https://github.com/pondersource/nc-sciencemesh) into the `apps/sciencemesh` folder (make sure you use that exact path, so not `apps/nc-sciencemesh` or anything else) of your Nextcloud repo.
* Log in as `einstein`, go to apps and activate the Sciencemesh app
* Log in as `tester` and do the same
* You can now cd into nextcloud/server/apps/sciencemesh and run `make test`

## reva unit tests with mocked Nextcloud server
* Clone [reva](https://github.com/cs3org/reva)
* Add Michiel's remote with `git remote add michielbdejong https://github.com/michielbdejong/reva
* `git fetch michielbdejong`
* `git checkout nextcloud-ocm-share`
*  Install reva's dependencies
*  Run `make build`
* Now run:
  * `go test -v github.com/cs3org/reva/pkg/storage/fs/nextcloud/...`
  * `go test -v github.com/cs3org/reva/pkg/share/manager/nextcloud/...`
  * `go test -v github.com/cs3org/reva/pkg/user/manager/nextcloud/...`
  * `go test github.com/cs3org/reva/pkg/ocm/share/manager/nextcloud/...`

## reva integration tests with mocked Nextcloud server
* cd to cs3org/reva/tests/integration
* `go test -v github.com/cs3org/reva/integration/grpc/...`

## reva unit & integration tests against Nextcloud+Sciencemesh
You need 5 terminal windows open: cs3org/reva, nextcloud/server, nextcloud/server/apps/sciencemesh, nextcloud/server/data/einstein, nextcloud/server/data
* In the nextcloud/server/apps/sciencemesh window, you can edit the php code to add debug statements and fixes
* In the nextcloud/server/data/einstein window, you see how einstein's data is changed as a side-effect of the tests
* In the nextcloud/server/data window, you can `tail -f nextcloud.log` to see `500 internal server error`s
* In the nextcloud/server window you can run `php -S localhost:8080` and view the logs, e.g. when API endpoints are 404s etc, and when you `error_log` in your php
* In the reva window, run:
  * `NEXTCLOUD=http://einstein:relativity@localhost:8080/index.php go test -v github.com/cs3org/reva/pkg/storage/fs/nextcloud/...`
  * `NEXTCLOUD=http://einstein:relativity@localhost:8080/index.php go test -v github.com/cs3org/reva/pkg/share/manager/nextcloud/...`
  * `NEXTCLOUD=http://einstein:relativity@localhost:8080/index.php go test -v github.com/cs3org/reva/pkg/user/manager/nextcloud/...`
  * `NEXTCLOUD=http://einstein:relativity@localhost:8080/index.php go test -v github.com/cs3org/reva/integration/grpc/...`
  * `NEXTCLOUD=http://einstein:relativity@localhost:8080/index.php go test github.com/cs3org/reva/pkg/ocm/share/manager/nextcloud/...`
