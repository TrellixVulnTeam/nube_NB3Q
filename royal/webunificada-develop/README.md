# Consum Drupal Distribution

This repository is a composer-based drupal distribution forked of [Lightning](https://www.drupal.org/project/lightning) 
and [docker4drupal](https://wodby.com/docs/stacks/drupal) for local development.

# Local develop environment

## Introduction

This local environment is based in [docker4drupal](https://wodby.com/docs/stacks/drupal), you can read more 
about this in:
 - Documentation: https://wodby.com/docs/stacks/drupal/local/ 
 - Repository: https://github.com/wodby/docker4drupal

**IMPORTANT:** The **docker_local** folder contains all the necessary files. All `docker-compose` commands
must be executed whitin it.

## Requirements
See https://wodby.com/docs/stacks/drupal/local/#requirements to know the docker requirementes
depending on your Operating System.

### Changes to docker-compose.yml
Any configuration changes to `docker-compose.yml` file should be in these files:

`.env` to store all variables

`docker-compose.override.yml` to override docker-compose settings


### Linux
Copy `example.env` to `.env`

### Windows
Copy `example.env` to `.env`

Copy `docker-compose.override-windows.yml` to `docker-compose.override.yml`

### Mac
Copy `example.env` to `.env` 

comment `#PHP_TAG=7.3-dev-4.15.2` and uncomment `PHP_TAG=7.3-dev-macos-4.15.1`

Copy `docker-compose.override-mac.yml` to `docker-compose.override.yml`

## Usage
`docker-compose up -d` start the containers in the background and leaves 
them runing. First time docker pull images, build volumes and 
start the containers and linked services. https://docs.docker.com/compose/reference/up/

`docker-compose stop` stop running containers.

`docker-compose exec SERVICE COMMAND [ARGS]` execute commands in containers (example: `docker-compose exec php bash` to 
get a interactive promt inside php container, or 
`docker-compose exec php drush cr` to clear drupal cache).

## Install drupal-consum
1. Start the containers
    - `cd path-to-drupal-repository/docker_local`
    - `docker-compose up -d` 
2. Once docker-compose is running and the containers are up
    - `docker-compose exec php bash`
3. Download drupal core, contrib modules, contrib themes, libraries and all of their 
respective dependencies.
    - `composer install`
4. Uncompres and import the dump into drupal database
    - `gzip -dk db/consumDBv1.sql.gz`
    - `drush sql-cli < db/consumDBv1.sql`
5. Import latest configuration changes 
    - `drush cim sync -y`
6. Launch drupal database updates 
    - `drush updb -y`

Now, you can test in http://consum.docker.localhost/ the website is ok.


## Know issues
**Doker for Windows** has problems with the user inside the php container 
[even using the root user](https://wodby.com/docs/stacks/drupal/local/#windows_1) 
and in some cases may cause problems to access some files like _settings.php_. 
You can check this with any drush command, like `drush st`. If you get an Error 
response, you can solve it changing permissions to drupal folders and files: 
`chmod -R 755 /var/www/html`
 
## Solr

Once the container are up & running, a collection is needed in solr search engine to allow drupal index the data in solr. To do this perform:

1. Create solr core:
    - `docker-compose exec solr solr create -c consumcollection -n data_driven_schema_configs`
2. Check the conection from Drupal to Solr is ok in http://consum.docker.localhost/admin/config/search/search-api

3.  Get the config file from Drupal and put into Solr Server
    - Go to http://consum.docker.localhost/admin/config/search/search-api 
and select “_Get config.zip_” in the context menu or press the button “_Get config.zip_” in 
http://consum.docker.localhost/admin/config/search/search-api/server/solr/edit
    - Copy downloaded zip file into solr container: 
        - `docker cp /path-to-file/solr_8.x_config.zip consum_solr:/opt/solr/server/solr/consumcollection/conf`
    - extract zip file 
        - `docker-compose exec solr bash`
        - `cd /opt/solr/server/solr/consumcollection/conf`
        - `unzip -o solr_8.x_config.zip`
4. Reload solr core
    - Inside Solr container: 
        - `make reload core=consumcollection -f /usr/local/bin/actions.mk`
    - From host: 
         - `docker-compose exec solr make reload core=consumcollection -f /usr/local/bin/actions.mk`

you can test how it works by creating content and searching in Drupal: http://consum.docker.localhost/search?keywords=some-word
 
or http://solr.consum.docker.localhost:8983/solr/#/consumcollection/query

# Configuración por entornos

## settings.php
Se ha eliminado este archivo del repositorio para que no se vea afectado al desplegar en los diferentes entornos,
asímismo se ha añadido exclusión en **.gitignore** para que no se añada automáticamente.

En su lugar se ha añadido **copy.settings.php** que es una copia del que debería estar en el entorno de desarrollo de
manera que se pueda utilizar copiandolo.

## Config Split
Se ha añadido el módulo [config_split](https://www.drupal.org/project/config_split) que permite establecer configuraciones diferentes por entornos de manera que se puede entre
otras cosas utilizar algunos modulos en entorno de desarrollo que luego no se van a utilizar en producción o tener
diferentes configuraciones por ejemplo para Google Analytics u otros módulos que necesiten parametros diferentes.

Para ello es necesario incluir las últimas lineas de **copy.settings.php** en nuestro settings si ya lo tenemos. Al mismo 
tiempo debemos definir la variable de entorno `DRUPAL_ENVIRONMENT=local` en nuestro fichero **.env** y lanzar 
`docker-compose up -d` para que actualice la variable en el contenedor de php.
