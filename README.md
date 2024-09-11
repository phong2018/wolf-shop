# Coding assessment - PHP Version

## Requirement

- php 8.1
- mysql 8.0.3

## In case you want use docker for local

- cd laradock
- cp .env.example .env
- docker compose up nginx php-fpm mysql
- add host
- 127.0.0.1 wolf-shop.local

## Install app

- docker exec -it laradock-workspace-1 /bin/bash  -> to access container (case using docker)
- composer install -> to install packages
- cp .env.example .env -> to copy .env file
- php artisan migration -> to migrate database
- php artisan app:import-items -> to run command import items
- access http://wolf-shop.local

## Coding analysis (phpstan), coding standard (ECS)

- composer phpstan
- composer check-cs
- composer fix-cs

# Runs PHPUnit test

- composer tests (this feature requires create database name default_test)
- composer test-coverage (this feature requires Xdebug or PCOV.) 

## In case you want to CICD

- using Dockerfile in /docker to build images