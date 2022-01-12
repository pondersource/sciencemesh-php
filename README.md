# sciencemesh-nextcloud
Connect your Nextcloud server to Sciencemesh

This repo is mainly an overview of the sciencemesh-nextcloud project

# Building the docs
 
```
pip install sphinx
make html
```

If you push to github, the change made to `./source` will be reflected 
on [the microsite](https://sciencemesh-nextcloud.readthedocs.io/).

# Development setup

There are two ways to set up your development environment for sciencemesh-nextcloud:

## Standard setup (recommended for Triantafullenia, Ismoil, Yvo, Bänz, Navid)
* Install nextcloud with the sciencemesh app, by following https://github.com/pondersource/nc-sciencemesh
* Switch your nextcloud to https://github.com/pondersource/server/tree/dynamic-shareproviders
* You can leave your apps/sciencemesh in the 'main' branch
* Work on issues from https://github.com/pondersource/nc-sciencemesh/issues
* Install reva to https://github.com/michielbdejong/reva/tree/sciencemesh
* Work on issues that were assigned to you in the current sprint in https://github.com/pondersource/sciencemesh-nextcloud/issues

## Docker-based setup (recommended for Michiel)
* Use https://github.com/cs3org/ocm-test-suite/tree/revanc and follow the instructions there
* Work on https://github.com/pondersource/sciencemesh-nextcloud/issues/35

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

# Install dependencies
For instance on Ubuntu:
```sh
apt update
apt install -y php7.3 git php-curl php-gd php7.3-opcache php7.3-xml php7.3-gd \
  php7.3-curl php7.3-zip php7.3-json libxml2 libxml2-dev php-xml php-mbstring \
  build-essential curl php7.3-sqlite3 php-xdebug php7.3-mbstring php-zip
```

# Run tests
You can run the tests of nc-sciencemesh itself as follows:

## nc-sciencemesh tests
* Clone [nextcloud/server](https://github.com/nextcloud/server)
* `cd server ; git submodule update --init`
* Run `php -S localhost:8080`, browse to https://localhost:8080, and make `einstein`/`relativity` the admin user. Alternatively:
```sh
export PHP_MEMORY_LIMIT="512M"
php console.php maintenance:install --admin-user einstein --admin-pass relativity
php console.php app:enable sciencemesh
```
* Also create a `tester`/`root` user
* Inside the tester's root folder(data/tester/files/sciencemesh), create some/path/test.json
* Clone [nc-sciencemesh]((https://github.com/pondersource/nc-sciencemesh) into the `apps/sciencemesh` folder (make sure you use that exact path, so not `apps/nc-sciencemesh` or anything else) of your Nextcloud repo.
* Log in as `einstein`, go to apps and activate the Sciencemesh app
* Log in as `tester` and do the same
* You can now cd into nextcloud/server/apps/sciencemesh and run
 ```
 XDEBUG_MODE=coverage make test
 ```
 or:
```sh
XDEBUG_MODE=coverage ./vendor/bin/phpunit --coverage-text
```

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

# Demo setup
On nc1.pondersource.net:
```sh
apt update ; apt install -y docker.io certbot
certbot certonly --standalone
# follow instructions
git clone https://github.com/cs3org/ocm-test-suite
cd ocm-test-suite
git checkout revanc
cd servers/apache-php
mkdir tls
cp /etc/letsencrypt/live/nc1.pondersource.net/fullchain.pem tls/nc1.crt
cp /etc/letsencrypt/live/nc1.pondersource.net/privkey.pem tls/nc1.key
docker build -t apache-php .
cd ../nextcloud
docker build -t nextcloud .
cd ../nc1
docker build -t nc1 .
docker run -d --network=host -e MARIADB_ROOT_PASSWORD=eilohtho9oTahsuongeeTh7reedahPo1Ohwi3aek --name=maria1.docker mariadb --transaction-isolation=READ-COMMITTED --binlog-format=ROW --innodb-file-per-table=1 --skip-innodb-read-only-compressed
docker run --network=host -d nc1
```

On nc2.pondersource.net:
```sh
apt update ; apt install -y docker.io certbot
certbot certonly --standalone
# follow instructions
git clone https://github.com/cs3org/ocm-test-suite
cd ocm-test-suite
git checkout revanc
cd servers/apache-php
mkdir tls
cp /etc/letsencrypt/live/nc2.pondersource.net/fullchain.pem tls/nc2.crt
cp /etc/letsencrypt/live/nc2.pondersource.net/privkey.pem tls/nc2.key
docker build -t apache-php .
cd ../nextcloud
docker build -t nextcloud .
cd ../nc2
docker build -t nc2 .
docker run -d --network=host -e MARIADB_ROOT_PASSWORD=eilohtho9oTahsuongeeTh7reedahPo1Ohwi3aek --name=maria2.docker mariadb --transaction-isolation=READ-COMMITTED --binlog-format=ROW --innodb-file-per-table=1 --skip-innodb-read-only-compressed
docker run --network=host -d nc2
```

On reva1.pondersource.net:
```sh
apt update ; apt install -y docker.io certbot
certbot certonly --standalone
# follow instructions
git clone https://github.com/cs3org/ocm-test-suite
cd ocm-test-suite
git checkout revanc
cd servers/revad
mkdir tls
cp /etc/letsencrypt/live/revad1.pondersource.net/fullchain.pem tls/server.cert
cp /etc/letsencrypt/live/revad1.pondersource.net/privkey.pem tls/server.key
touch tls/example.crt
docker build -t revad .
docker run --network=host -d revad
```

On reva2.pondersource.net:
```sh
apt update ; apt install -y docker.io certbot
certbot certonly --standalone
# follow instructions
git clone https://github.com/cs3org/ocm-test-suite
cd ocm-test-suite
git checkout revanc
cd servers/revad
mkdir tls
cp /etc/letsencrypt/live/revad2.pondersource.net/fullchain.pem tls/server.cert
cp /etc/letsencrypt/live/revad2.pondersource.net/privkey.pem tls/server.key
touch tls/example.crt
docker build -t revad .
docker run --network=host -d revad
```
