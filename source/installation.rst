Installation Guide
==================

The ScienceMesh-Nextcloud connection helps sites who run Nextcloud as their enterprise file sync and share system to join the ScienceMesh.

There are number of things you will need to do get this working for your site:

Enable dynamic share providers in Nextcloud
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Apply the [dynamic-shareproviders]](https://github.com/pondersource/server/tree/dynamic-shareproviders) branch to your Nextcloud installation.
This is necessary for Nextcloud to recognize shares of type the 'ScienceMesh'.

Install Revad Edge
~~~~~~~~~~~~~~~~~~
```
git clone https://github.com/cs3org/reva
cd reva
git checkout edge
make deps
make build
```

Configure revad
~~~~~~~~~~~~~~~

Something like `./mesh.toml`. This will probably require some experimentation. You can contact @michielbdejong in https://gitter.im/cs3org/REVA for help.

Install the ScienceMesh app
~~~~~~~~~~~~~~~~~~~~~~~~~~~
In your Nextcloud apps folder, run:
```
git clone https://github.com/pondersource/nc-sciencemesh sciencemesh
```
Enable the app in the Nextcloud admin dashboard.
This will cause a few necessary database tables to be created.


First use
~~~~~~~~~
From there on, follow the `admin guide`_ 

__
.. _admin guide: admin.html
