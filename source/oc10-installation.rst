Owncloud Installation Guide
==================

The ScienceMesh-OwnCloud connection helps sites who run OwnCloud10 as their enterprise file sync and share system to join the ScienceMesh.

For OwnCloud 10 there is `oc-sciencemesh <https://github.com/pondersource/core/tree/reva-sharees>`__

Addition to this documentation also, you can refer to some links in `this Gitter thread <https://gitter.im/sciencemesh/task-force-technical?at=630dc4aa9d3c186299d87893>`__,
and ask your questions there.
There are number of things you will need to do get this working for your site:

Enable dynamic share providers in Owncloud
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Apply the `reva-sharees <https://github.com/pondersource/core.git>`__ branch to your ownCloud installation.
This is necessary for OwnCloud to recognize shares of type the 'ScienceMesh'.

Add this line into configuration file of Owncloud "config/config.php":
  'sharing.managerFactory' => 'OCA\\ScienceMesh\\ScienceMeshProviderFactory'

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
In your OwnCloud apps folder, run:

   git clone --depth=1 --branch review-factory  https://github.com/pondersource/oc-sciencemesh sciencemesh

Enable the app in the Owncloud admin dashboard.
This will cause a few necessary database tables to be created.


First use
~~~~~~~~~
From there on, follow the `admin guide <admin.html>`_.
 
