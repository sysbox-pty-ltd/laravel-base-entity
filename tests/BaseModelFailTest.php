<?php

namespace Sysbox\LaravelBaseEntity\Tests;
/**
 * Created by PhpStorm.
 * User: helin16
 * Date: 2020-05-05
 * Time: 19:55
 */

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Sysbox\LaravelBaseEntity\BaseModel;
use Sysbox\LaravelBaseEntity\Exception;
use Tests\TestCase;

class FakeBaseModelChild extends BaseModel {}

class BaseModelFailTest extends TestCase
{
    /**
     * GIVEN a BaseModel Class and no config file registered
     * WHEN it's been initiated
     * THEN an error should return
     *
     * @test
     */
    public function aBaseModelWillFailToBootWhenNoConfigFile() {
        // GIVEN a BaseModel Class and no config file registered

        // WHEN it's been initiated
        // an error should return
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('No config file found, try `php artisan vendor:publish --provider="Sysbox\LaravelBaseEntity\LaravelBaseEntityServiceProvider"` first.');
        new FakeBaseModelChild();
    }

    /**
     * GIVEN a BaseModel Class with a config file containing an empty UserClass field registered
     * WHEN it's been initiated
     * THEN an error should return
     *
     * @test
     * @dataProvider dataProviderForaBaseModelWillFailToBootWhenConfigFileContainsEmptyUserClass
     */
    public function aBaseModelWillFailToBootWhenConfigFileContainsEmptyUserClass($userClassName, $errorMsg) {
        // GIVEN a BaseModel Class and no config file registered
        Config::set('laravelBaseEntity.user_class', $userClassName);

        // WHEN it's been initiated
        // an error should return
        $this->expectException(Exception::class);
        $this->expectExceptionMessage($errorMsg);
        new FakeBaseModelChild();
    }

    /**
     * data provider for aBaseModelWillFailToBootWhenConfigFileContainsEmptyUserClass
     * @return array
     */
    public function dataProviderForaBaseModelWillFailToBootWhenConfigFileContainsEmptyUserClass() {
        return [
            ['', 'No user_class defined in module\'s config file, please define user_class under config file [config/laravelBaseEntity.php].'],
            ['fake_className', 'Invalid user_class[fake_className] defined in module\'s config file [config/laravelBaseEntity.php].'],
            [\stdClass::class, 'Invalid user_class[' . \stdClass::class . '] in config file [config/laravelBaseEntity.php], it needs to be a child class of ' . Model::class . '.'],
        ];
    }
}