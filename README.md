# Coding assessment - PHP Version

## Requirement

- php 8.1
- mysql 8.0.3

## Build development environment in local

- you can use: XAMPP, LAMP, or Laragon... In case, you use docker, I guide below:
- cd laradock
- cp .env.example .env
- docker compose up nginx php-fpm mysql
- the command above will build Nginx (with the virtual host wolf-shop.local), PHP 8.1, and MySQL 8.0 (with database name: default; user=root; password=root; testing database: default_test)
- add the following entry to your hosts file: 127.0.0.1 wolf-shop.local

## Install app

- in case using docker, need to access container: docker exec -it laradock-workspace-1 /bin/bash
- then install app following steps:
- composer install -> to install packages
- cp .env.example .env -> to copy .env file
- update value for .env (database config, cloudubary credentials, basic authentication credentials)
- php artisan migration -> to migrate database
- php artisan app:import-items -> to run command import items
- access http://wolf-shop.local

## Use Postman to call api

- import the API into Postman from the file located in the root directory: Wolf-shop.postman_collection.json
- there are 2 api: Get List Items (GET:api/items), Upload image Item (PATCH:api/items/upload-image)
- you can preview the result in evidences/reslut-update-image.png

## Coding analysis (phpstan), coding standard (ECS)

- composer phpstan
- composer check-cs
- composer fix-cs
- you can preview the result in evidences/check-code-phpstan.png

## Runs PHPUnit test

- composer tests (need create database name default_test, If you are using Laradock, I have already created the default_test database for you)
- composer test-coverage (this feature requires Xdebug or PCOV.) 
- you can preview the result in evidences/run-test.png

## In case you want to CICD

- using Dockerfile in /docker to build images

## Tasks completed in the project

- Integrate laravel into project, using coding analysis (phpstan), coding standard (ECS)
- Refactor the WolfService and update the latest requirements
- Store data items using mysql database
- Ceate a console command to import Item, To run command: php artisan app:import-items 
- Create API endpoint to upload imgUrl for item via https://cloudinary.com (config credentials in .env). Has basic authentication (the credentials is hard code in .env: API_USERNAME=username; API_PASSWORD=password)
- Write test for all features: WolfServiceTest, ImportItemsServiceTest, UploadImageItemServiceTest. To run test: composer tests
- Add, custom laradock to develop in localhost, in folder /laradock
- Create Dockerfile to build images for auto deloying with CICD, in folder /docker
- The project is developed with adherence to the KISS, DRY, YAGNI, and SOLID principles, ensuring it's ready for easy expansion and maintenance.

Thank you!