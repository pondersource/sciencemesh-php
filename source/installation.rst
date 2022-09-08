NextCloud Installation Guide
==================

The ScienceMesh-Nextcloud connection helps sites who run Nextcloud as their enterprise file sync and share system to join the ScienceMesh.

For ownCloud 10 there is `oc-sciencemesh <https://github.com/pondersource/oc-sciencemesh>`__
but the documentation is unfortunately `not complete yet <https://github.com/pondersource/sciencemesh-nextcloud/issues/69>`__.

For now, you can refer to some links in `this Gitter thread <https://gitter.im/sciencemesh/task-force-technical?at=630dc4aa9d3c186299d87893>`__,
and ask your questions there.
There are number of things you will need to do get this working for your site:

Enable dynamic share providers in Nextcloud
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Apply the `dynamic-shareproviders <https://github.com/pondersource/server/tree/dynamic-shareproviders>`__ branch to your Nextcloud installation.
This is necessary for Nextcloud to recognize shares of type the 'ScienceMesh'.

Install Revad
~~~~~~~~~~~~~
See also https://github.com/cs3org/reva::

  git clone https://github.com/cs3org/reva
  cd reva
  make deps
  make build

Configure revad
~~~~~~~~~~~~~~~

Something like `./mesh.toml`. This will probably require some experimentation. You can contact @michielbdejong in https://gitter.im/cs3org/REVA for help.
See also the `ocm-test-suite revad configs <https://github.com/cs3org/ocm-test-suite/tree/main/servers/revad>`__ for examples.

Install the ScienceMesh app
~~~~~~~~~~~~~~~~~~~~~~~~~~~
In your Nextcloud apps folder, run::

  git clone https://github.com/pondersource/nc-sciencemesh sciencemesh
  cd sciencemesh
  Make

For Owncloud In your Owncloud apps folder, run::

  git clone https://github.com/pondersource/oc-sciencemesh sciencemesh
  cd sciencemesh
  Make

Enable the app in the Nextcloud/Owncloud admin dashboard.
This will cause a few necessary database tables to be created.


First use
~~~~~~~~~
From there on, follow the `admin guide <admin.html>`_.
 
