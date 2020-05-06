<?php
/**
 * Created by PhpStorm.
 * User: helin16
 * Date: 2020-05-05
 * Time: 21:18
 */

namespace Sysbox\LaravelBaseEntity;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class LaravelBaseEntity
{
    /**
     * Booting an Entity
     *
     * @throws Exception
     * @throws \ReflectionException
     */
    public function bootModel() {
        $configFilePath = 'config/laravelBaseEntity.php';
        $userClassname = $this->getUserClassName();
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
        return $this;
    }

    /**
     * Getting the classname of the User for Created_by_id and updated_by_id
     *
     * @return string
     */
    public function getUserClassName() {
        return Config::get('laravelBaseEntity.user_class');
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getSystemUserId() {
        $systemUserId = Config::get('laravelBaseEntity.system_user_id');
        if (trim($systemUserId) !== '') {
            return $systemUserId;
        }
        return $this->genHashId(get_class($this));
    }

    /**
     * Generate Hash for Base Model's ID
     *
     * @param $class_name
     * @param null $userId
     * @param Carbon|null $time
     * @return string
     * @throws \Exception
     */
    public function genHashId($class_name, $userId = null, Carbon $time = null)
    {
        if (! $time instanceof Carbon) {
            $time = Carbon::now();
        }

        return md5(implode('_', [$class_name, $userId, $time->getTimestamp(), random_int(0, PHP_INT_MAX)]));
    }

}