## Introducción

Entorno especificamente diseñado para el servidor de desarrollo drupal99, es una copia reducida de [docker4drupal](https://github.com/wodby/docker4drupal)
con los servicios necesarios mariadb y solr. Adicionalmente se pueden levantar phpmyadmin, node (para compilación de CSS y js)
estos ultimos ubicados en el archivo `docker-compose.override-example.yml`


Documentación: https://wodby.com/docs/stacks/drupal/local/ 

Repositorio: https://github.com/wodby/docker4drupal


## Instalación
Copiar `example.env` en `.env`

Copiar `docker-compose.override-example.yml` en `docker-compose.override.yml`

Ejecutar `docker-compose up -d`
 
