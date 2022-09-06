Reva Installation Guide using Helm Chart
==================
This section tries to provide a detailed step by step documentation for reva installation  on kubernetes using helm chart.

Kubernetes
==================
If you have planty of resources and infrustructures and you can use Kubernetes itself, but if you want run reva on a small machine 
using clustrisation infrustracture you can install the `microK8S  <https://microk8s.io/>`__.

Helm Charts
==================
You can find Helm chart documentation `here <https://helm.sh/docs/intro/quickstart/> `__.

Instalation steps:
~~~~~~~~~~~~~~~~~~
1- clone **feature/oc-config** branch of the reva helm chart code from `this repo <https://github.com/pondersource/charts>`__ .
2- enter into **revad** folder. 
:: 
  first of all check the ingress class name of the installed kubernetes controller that you already have.
  using this commands:
  1- kubectl get all  -n ingress
     To get the name of ingress controller pod. 
  2- kubectl describe -n ingress <ingress-controller-pod_name>
     To list the pod details and then check **--ingress-class** value.
     if it is nginx it is ok and you donot need to change the value of *ingress.services.http.annotations.kubernetes.io/ingress.class*
     and *ingress.services.grpc.annotations.kubernetes.io/ingress.class*. but if it has deferebt value (specially when you are using microk8s),
     you should change dose values in the values.yaml file.

3- you can get the generated manifests befor installing by running below command and check the result to confirm its validity:
:: 
  helm template --debug <THE_CHOOSEN_NAME> .\
   --set EFSS=OWNCLOUD \
   --set OCURL=<Url of you NC/OC10 hosting machine> \
   --set SharedSecret=thisismysecret \
   --set HostDomain=<Domain name of your reva hosted machine> \
   --set ingress.services.http.hostname=<Domain name of your reva hosted machine> 
   --set ingress.services.grpc.hostname=<Domain name of your reva hosted machine> 
   --set ingress.enabled=true

4- finally this is the installation command:
::
   helm install <THE_CHOOSEN_NAME> .\
   --set EFSS=OWNCLOUD \
   --set OCURL=<Url of you NC/OC10 hosting machine> \
   --set SharedSecret=thisismysecret \
   --set HostDomain=<Domain name of your reva hosted machine> \
   --set ingress.services.http.hostname=<Domain name of your reva hosted machine> 
   --set ingress.services.grpc.hostname=<Domain name of your reva hosted machine> 
   --set ingress.enabled=true

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
 