# Coding assessment - PHP Version

## Requirement

- php 8.1
- mysql 8.0.3

## Ins case you want use docker for local

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

## Runs PHPUnit test

- composer tests

## Runs PHPUnit test

- composer tests

## Runs PHPUnit with report

- composer test-coverage (This feature requires Xdebug or PCOV.)

## Runs Easy Coding Standard (ECS)

- composer check-cs

## Runs Easy Coding Standard (ECS) & automatically fix any violations

- composer fix-cs

## runs PHPStan

- composer phpstan