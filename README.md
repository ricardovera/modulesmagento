# modulesmagento
Tareas Magento
Para la instalación de ambiente local usó el siguiente proyecto en Docker:

https://github.com/markshust/docker-magento 
para lenvatarlo en ubuntu ( sistema que yo uso )

curl -s https://raw.githubusercontent.com/markshust/docker-magento/master/lib/onelinesetup | bash -s -- magento.test 2.4.6 community
Con esto levantamos el enterno automatico en la versión 2.4.6.

Para la instalación de sample de magento con el siguiente comando :
bin/magento sampledata:deploy en la consolta

