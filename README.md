<h1>Base Entity for Laravel models</h1>

This package provides the base model for laravel models with 6 key fields:
* id ( non numeric / non incremental ) 
* active ( boolean )
* created_by_id ( the id of the creator )
* created_at ( the timestamp of the creating )
* updated_by_id ( the id of the updater )
* updated_at ( the timestamp of the updating )

## Installation

Via Composer

```bash
$ composer require sysbox/laravel-base-entity
```

## Usage

###step 1
Publish the config file
```bash
$ php artisan vendor:publish --provider='Sysbox\LaravelBaseEntity\LaravelBaseEntityServiceProvider'
```
###step 2
Setup your laravel's interface model, like below: 
edit <laravel-root-dir>/app/User.php
```php

```
