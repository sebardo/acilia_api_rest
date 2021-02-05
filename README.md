
Acilia API REST
=============

About
-----

This repository contains all necessary code in order to run the Acilia Api REST as part of a tecnical challenge

Development
===========

The project is built with a several libraries as follow:

- composer create-project symfony/skeleton:"^4.4" acilia_api_rest
- composer require symfony/orm-pack (database conections)
- composer require annotations (route annotations)
- composer require symfony/maker-bundle --dev (entty creator)
- composer require symfony/twig-bundle (templates)
- composer require symfony/asset 
- composer require nelmio/api-doc-bundle (api documentation)
- composer require trikoder/oauth2-bundle nyholm/psr7 (oauth2 authentication/authorization)
- composer require --dev symfony/phpunit-bridge (testing)
- composer require --dev symfony/browser-kit symfony/css-selector

Running the project 
---------------------------------------

This project requires `postgres` database with `postgres` user without password to running in the system. If you want other database engine just modify .env file

Clone the repository and access to folder created, run composer and create database, schema and start server 
```
git clone https://github.com/sebardo/acilia_api_rest.git
cd acilia_api_rest
composer install
./bin/console doctrine:database:create
./bin/console doctrine:schema:create
symfony server:start
```

Generating Crypto Keys
----------------------

In order to enable the OAuth authentication flow a public/private keypair
must be generated. Under root directory `/`, run:

```bash
openssl genrsa -out private.key 2048
openssl rsa -in private.key -pubout -out public.key
```

Now can create OAuth client 
```
./bin/console trikoder:oauth2:create-client
```
And add CLIENT_ID and CLIENT_SECRET returned by command above on `phpunit.xml.dist` file

![PHPUNIT](https://i.ibb.co/544CBy6/phpunit.png)

Enjoy it!
----------------------
Now you can visit API REST documentation page and enjoy it! testing categort and product endpoints at
https://127.0.0.1:8000/api/doc

![APIREST](https://i.ibb.co/m4jTKVG/docs.png)

Testing
----------------------
Can run testing to check all worl well

```
./bin/phpunit
```

![Testing](https://i.ibb.co/4dvfnvy/test.png)

Author
------

Dario Sebastian Sasturain 4/2021
