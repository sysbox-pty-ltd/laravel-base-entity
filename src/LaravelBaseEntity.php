<?php
/**
 * Created by PhpStorm.
 * User: helin16
 * Date: 2020-05-05
 * Time: 21:18
 */

namespace Sysbox\LaravelBaseEntity;


class LaravelBaseEntity
{
    public static function boot() {
        $configFilePath = 'config/laravelBaseEntity.php';
        $userClassname = config('laravelBaseEntity.user_class');
        if ($userClassname === null) {
            throw new Exception('No config file found, try `php artisan vendor:publish --provider="Sysbox\LaravelBaseEntity\LaravelBaseEntityServiceProvider"` first.');
        }
        if (trim($userClassname) === '') {
            throw new Exception('No user_class defined in module\'s config file, please define user_class under config file [' . $configFilePath . '].');
        }
        if (!class_exists($userClassname)) {
            throw new Exception('Invalid user_class[' . $userClassname . '] defined in module\'s config file [' . $configFilePath . '].');
        }
    }
}