<?php
/**
 * Created by PhpStorm.
 * User: helin16
 * Date: 2020-05-05
 * Time: 21:18
 */

namespace Sysbox\LaravelBaseEntity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class LaravelBaseEntity
{
    public function boot() {
        $configFilePath = 'config/laravelBaseEntity.php';
        $userClassname = Config::get('laravelBaseEntity.user_class');
        if ($userClassname === null) {
            throw new Exception('No config file found, try `php artisan vendor:publish --provider="Sysbox\LaravelBaseEntity\LaravelBaseEntityServiceProvider"` first.');
        }
        if (trim($userClassname) === '') {
            throw new Exception('No user_class defined in module\'s config file, please define user_class under config file [' . $configFilePath . '].');
        }
        if (!class_exists($userClassname)) {
            throw new Exception('Invalid user_class[' . $userClassname . '] defined in module\'s config file [' . $configFilePath . '].');
        }
        $obj = new \ReflectionClass($userClassname);
        if(!$obj->isSubclassOf(Model::class)) {
            throw new Exception('Invalid user_class[' . $userClassname . '] in config file [' . $configFilePath . '], it needs to be a child class of ' . Model::class . '.');
        }
    }
}