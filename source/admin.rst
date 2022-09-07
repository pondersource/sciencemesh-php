Admin Documentation
===================

ScienceMesh Nextcloud:
===================

`iopUrl` is url of your running reva instance.
Configure "iopUrl" to point to your revad instance. You can set this value in your Nextcloud database::

  insert into oc_appconfig (appid, configkey, configvalue) values ('sciencemesh', 'iopUrl', 'https://revanc1.docker/');

There is also a `shared_secret` that must be same in reva.toml file and Nextcloud database. This secret use to reva can authenticate the requests from Nextcloud.

Make sure that `revaSharedSecret` in there matches the `shared_secret` entry in the following sections of your revad.toml file:


* [grpc.services.storageprovider.drivers.nextcloud]
* [grpc.services.authprovider.auth_managers.nextcloud]
* [grpc.services.userprovider.drivers.nextcloud]
* [grpc.services.ocmcore.drivers.nextcloud]
* [grpc.services.ocmshareprovider.drivers.nextcloud]

There must also exist a row in Nextclouddatabase for `revaSharedSecret`.

`revaLoopbackSecret` is a key in Nextcloud for authenticating reva users by Nextcloud. reva sends this key in body instead of real user's password. This loopback secret send from Nextcloud to reva in request's body.

If this key does not exists in Nextcloud database insert a random string for this key as value.

Set the base address of running Nextcloud instance in the following sections of reva.toml file:


* [grpc.services.storageprovider.drivers.nextcloud]
* [grpc.services.authprovider.auth_managers.nextcloud]
* [grpc.services.userprovider.drivers.nextcloud]
* [http.services.dataprovider.drivers.nextcloud]


ScienceMesh Owncloud:
=====================


`iopUrl` is url of your running reva instance.
Configure "iopUrl" to point to your revad instance. You can set this value in your Owncloud database::

  insert into oc_appconfig (appid, configkey, configvalue) values ('sciencemesh', 'iopUrl', 'https://revanc1.docker/');

There is also a `shared_secret` that must be same in reva.toml file and Owncloud database. This secret use to reva can authenticate the requests from Owncloud.

Make sure that `revaSharedSecret` in there matches the `shared_secret` entry in the following sections of your revad.toml file:


* [grpc.services.storageprovider.drivers.nextcloud]
* [grpc.services.authprovider.auth_managers.nextcloud]
* [grpc.services.userprovider.drivers.nextcloud]
* [grpc.services.ocmcore.drivers.nextcloud]
* [grpc.services.ocmshareprovider.drivers.nextcloud]

There must also exist a row in Owncloud database for `revaSharedSecret`.

`revaLoopbackSecret` is a key in Owncloud for authenticating reva users by Owncloud. reva sends this key in body instead of real user's password. This loopback secret send from Owncloud to reva in request's body.

If this key does not exists in Owncloud database insert a random string for this key as value.

Set the base address of running Owncloud instance in the following sections of reva.toml file:


* [grpc.services.storageprovider.drivers.nextcloud]
* [grpc.services.authprovider.auth_managers.nextcloud]
* [grpc.services.userprovider.drivers.nextcloud]
* [http.services.dataprovider.drivers.nextcloud]

Registration flow API
~~~~~~~~~~~~~~~~~~~~~
You can set some manually estimated statistics data in the admin interface of the Nextcloud and Owncloud app.
You can follow the instructions on https://sciencemesh.io/ to add your site to the ScienceMesh directory.
Once that is complete, other sites will update their approved providers whitelists.