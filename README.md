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

- composer install
- cp .env.example .env
- docker exec -it laradock-workspace-1 /bin/bash
- php artisan key:generate
- access http://wolf-shop.local

## Runs PHPUnit test, check coding standard (ECS) & automatically fix any violations

- composer tests
- composer test-coverage (This feature requires Xdebug or PCOV.) 
- composer check-cs
- composer fix-cs
- composer phpstan

## In case you want to CICD

- using Dockerfile in /docker to build images