<?php

namespace Sysbox\LaravelBaseEntity\Tests;
/**
 * Created by PhpStorm.
 * User: helin16
 * Date: 2020-05-05
 * Time: 19:55
 */

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Sysbox\LaravelBaseEntity\Exception;
use Sysbox\LaravelBaseEntity\LaravelBaseEntity;
use Tests\TestCase;

class LaravelEntitySuccessTest extends TestCase
{
    /**
     * @test
     * @dataProvider dataProviderForaLaravelBaseEntityCanGetUserClassNameFromConfig
     */
    public function aLaravelBaseEntityCanGetUserClassNameFromConfig($field, $value, $funcName) {
        Config::set('laravelBaseEntity.' . $field, $value);

        $entity = new LaravelBaseEntity();

        $this->assertEquals($value, $entity->$funcName());
    }

    /**
     * @return array
     */
    public function dataProviderForaLaravelBaseEntityCanGetValuesFromConfig() {
        return [
            ['user_class', 'fake_user_name', 'getUserClassName'],
            ['system_user_id', 'fakeUserId', 'getSystemUserId'],
        ];
    }

    /**
     * @test
     */
    public function aLaravelBaseEntityCanGetSystemUserIdFromConfigWhenNoSystemIdProvided() {
        Carbon::setTestNow(Carbon::createFromDate(2020, 1, 1));
        $expected = md5(implode('_', [LaravelBaseEntity::class, null, Carbon::now()->getTimestamp(), 1]));
        $entity = new LaravelBaseEntity();

        $this->assertEquals($expected, $entity->getSystemUserId());
    }
}