<?php
/**
 * Created by PhpStorm.
 * User: helin16
 * Date: 2020-05-05
 * Time: 21:18
 */

namespace Sysbox\LaravelBaseEntity;

use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Support\Facades\Config;
use Sysbox\LaravelBaseEntity\Interfaces\UserReferable;

class LaravelBaseEntity
{
    const PACKAGE_NAME = 'LaravelBaseEntity';
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
        if(!$obj->implementsInterface(UserReferable::class)) {
            throw new Exception('Invalid user_class[' . $userClassname . '] in config file [' . $configFilePath . '], it needs to implement interface: ' . UserReferable::class . '.');
        }
        return $this;
    }

    /**
     * Getting the classname of the User for Created_by_id and updated_by_id
     *
     * @return string
     */
    public function getUserClassName() {
        return $this->getSystemConfig('user_class');
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getSystemUserId() {
        $systemUserId = $this->getSystemConfig('system_user_id');
        if (trim($systemUserId) !== '') {
            return $systemUserId;
        }
        return self::PACKAGE_NAME . '_sys_user_id';
    }

    /**
     * @param $key
     * @return mixed
     */
    private function getSystemConfig($key) {
        return Config::get(self::PACKAGE_NAME .  '.' . $key);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getCurrentUserId() {
        $userClassname = $this->getUserClassName();
        $currentUser = $userClassname::getCurrentUser();
        if ($currentUser instanceof $userClassname) {
            return $currentUser->getUserId();
        }
        return $this->getSystemUserId();
    }

    /**
     * @return mixed
     */
    public function getNow() {
        return Carbon::now();
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
            $time = $this->getNow();
        }
        if (trim($userId) === '') {
            $userId = $this->getSystemUserId();
        }

        return md5(implode('_', [$class_name, $userId, $time->getTimestamp(), random_int(0, PHP_INT_MAX)]));
    }

    /**
     * @param Blueprint $blueprint
     * @param string $columnName
     *
     * @return Blueprint
     */
    public function addIdColumn(Blueprint $blueprint, $columnName = 'id')
    {
        $this->addHashIdColumn($blueprint, $columnName)->index();
        $blueprint->primary($columnName)->index();
        return $blueprint;
    }

    /**
     * Adding a hash id column to the table
     *
     * @param Blueprint $blueprint
     * @param $name
     * @return Blueprint
     */
    public function addHashIdColumn(Blueprint $blueprint, $name)
    {
        return $blueprint->string($name, 32);
    }

    /**
     * Add creation and update timestamps to the table.
     *
     * @param Blueprint $blueprint
     * @return Blueprint
     */
    public function addBasicLaravelBaseEntityColumns(Blueprint $blueprint)
    {
        $this->addIdColumn($blueprint);
        $blueprint->boolean('active')->index();
        $this->addHashIdColumn($blueprint, 'created_by_id')->index();
        $blueprint->timestamp('created_at')->nullable();
        $this->addHashIdColumn($blueprint, 'updated_by_id')->index();
        $blueprint->timestamp('updated_at')->nullable();
        return $blueprint;
    }

}