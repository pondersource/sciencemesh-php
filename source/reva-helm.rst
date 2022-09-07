Reva Installation Guide using Helm Chart
==================
This section tries to provide a detailed and step by step documentation for reva installation on kubernetes using helm chart.

Kubernetes
==================
If you have planty of resources and infrustructures, you can use the Kubernetes, but if you want to run reva on a small machine 
using clusterization infrustracture you can install the `microK8S  <https://microk8s.io>`__.

Helm Charts
==================
You can find the Helm chart documentation `here <https://helm.sh/docs/intro/quickstart>`__.

Instalation steps:
~~~~~~~~~~~~~~~~~~
1- clone **feature/oc-config** branch of the reva helm chart code from `this repo <https://github.com/pondersource/charts>`__ .
2- enter into **revad** folder. 
:: 
  first of all check the ingress class name of the ingress controller that is
  running on your Kubernetes using this commands:
  1- **kubectl get all  -n ingress**
     To get the name of ingress controller pod. 
  2- ``kubectl describe -n ingress <ingress-controller-pod_name>``
     To list the pod detailed information and then check the value of 
     the ``--ingress-class``. if it is nginx it is ok and you donot need to change
     the value of ``ingress.services.http.annotations.kubernetes.io/ingress.class``
     and *ingress.services.grpc.annotations.kubernetes.io/ingress.class*.
     but if it is different (specially when you are using microk8s), you should 
     change dose values in the values.yaml file.

3- (OPTIONAL) you can get the generated manifests before installing them by running below command and check the result to confirm its validity:
:: 
  ``helm template --debug <THE_CHOOSEN_NAME> .\
   --set EFSS=OWNCLOUD \
   --set OCURL=<Url of you NC/OC10 hosting machine> \
   --set SharedSecret=thisismysecret \
   --set HostDomain=<Domain name of your reva hosted machine> \
   --set ingress.services.http.hostname=<Domain name of your reva hosted machine> 
   --set ingress.services.grpc.hostname=<Domain name of your reva hosted machine> 
   --set ingress.enabled=true``

4- finally this is the installation command:
::
   ``helm install <THE_CHOOSEN_NAME> .\
   --set EFSS=OWNCLOUD \
   --set OCURL=<Url of you NC/OC10 hosting machine> \
   --set SharedSecret=thisismysecret \
   --set HostDomain=<Domain name of your reva hosted machine> \
   --set ingress.services.http.hostname=<Domain name of your reva hosted machine> 
   --set ingress.services.grpc.hostname=<Domain name of your reva hosted machine> 
   --set ingress.enabled=true``

Addition to this documentation also, you can refer to some links in `this Gitter thread <https://gitter.im/sciencemesh/task-force-technical?at=630dc4aa9d3c186299d87893>`__,
and ask your questions there.
