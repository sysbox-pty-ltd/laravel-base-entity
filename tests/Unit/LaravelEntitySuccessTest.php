<?php

namespace Sysbox\LaravelBaseEntity\Tests\Unit;
/**
 * Created by PhpStorm.
 * User: helin16
 * Date: 2020-05-05
 * Time: 19:55
 */

use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Sysbox\LaravelBaseEntity\Helpers\FakeUserReferableClass;
use Sysbox\LaravelBaseEntity\LaravelBaseEntity;
use const Sysbox\LaravelBaseEntity\RANDOM_INT_FOR_TESTING;
use Tests\TestCase;

class LaravelEntitySuccessTest extends TestCase
{
    /**
     * @test
     * @dataProvider dataProviderForaLaravelBaseEntityCanGetUserClassNameFromConfig
     */
    public function aLaravelBaseEntityCanGetUserClassNameFromConfig($field, $value, $funcName) {
        Config::set(LaravelBaseEntity::PACKAGE_NAME . '.' . $field, $value);

        $entity = new LaravelBaseEntity();

        $this->assertEquals($value, $entity->$funcName());
    }

    /**
     * @return array
     */
    public function dataProviderForaLaravelBaseEntityCanGetUserClassNameFromConfig() {
        return [
            ['user_class', 'fake_user_name', 'getUserClassName'],
            ['system_user_id', 'fakeUserId', 'getSystemUserId'],
        ];
    }

    /**
     * @test
     */
    public function aLaravelBaseEntityCanGetSystemUserIdFromConfigWhenNoSystemIdProvided() {

        $entity = new LaravelBaseEntity();

        Carbon::setTestNow(Carbon::createFromDate(2020, 1, 1));
        $expected = LaravelBaseEntity::PACKAGE_NAME . '_sys_user_id';
        $this->assertEquals($expected, $entity->getSystemUserId());
    }

    /**
     * @test
     * @dataProvider dataProviderForaLaravelBaseEntityCanGetHashId
     */
    public function aLaravelBaseEntityCanGetHashId($className, $userId, $carbonTime) {

        require_once __DIR__ . '/../Helpers/PhpUnitBuildinFuncs.php';
        if (!$carbonTime instanceof Carbon) {
            Carbon::setTestNow(Carbon::createFromDate(2020, 1, 1));
            $carbonTime = Carbon::now();
        }

        $expected = md5(implode('_', [$className, $userId, $carbonTime->getTimestamp(), RANDOM_INT_FOR_TESTING]));

        $entity = new LaravelBaseEntity();

        $this->assertEquals($expected, $entity->genHashId($className, $userId, $carbonTime));
    }

    /**
     * @return array
     */
    public function dataProviderForaLaravelBaseEntityCanGetHashId() {
        return [
            ['test', 'fake_user_id', Carbon::createFromDate(2020, 1, 1)],
            ['test', 'fake_user_id', ''],
            ['test', 'fake_user_id', null],
        ];
    }

    /**
     * @test
     */
    public function aLaravelBaseEntityCanGetNow() {

        Carbon::setTestNow(Carbon::createFromDate(2020, 1, 1));

        $entity = new LaravelBaseEntity();

        $this->assertEquals(Carbon::now(), $entity->getNow());
    }

    /**
     * GIVEN a class implements UserReferable
     * AND this classname has been set in the config file against: user_class
     * AND there is a current user set
     * WHEN LaravelBaseEntity is trying to get the current user id
     * THEN the user id will be returned.
     *
     * @test
     */
    public function aLaravelBaseEntityCanGetCurrentUserId() {
        // GIVEN a class implements UserReferable
        // AND this classname has been set in the config file against: user_class
        Config::set(LaravelBaseEntity::PACKAGE_NAME . '.user_class', FakeUserReferableClass::class );

        // WHEN LaravelBaseEntity is trying to get the current user id
        $entity = new LaravelBaseEntity();
        $currentUserId = $entity->getCurrentUserId();

        // THEN the user id will be returned.
        $this->assertEquals(FakeUserReferableClass::$currentUserId, $currentUserId);
    }
    /**
     * GIVEN a class implements UserReferable
     * AND this classname has been set in the config file against: user_class
     * AND there is no current user set
     * WHEN LaravelBaseEntity is trying to get the current user id
     * THEN the user id will be returned.
     *
     * @test
     */
    public function aLaravelBaseEntityCanGetSystemUserIdWhenNoCurrentUserSet() {
        // GIVEN a class implements UserReferable
        // AND this classname has been set in the config file against: user_class
        $fake_system_user_id = 'fake_system_user_id';
        Config::set(LaravelBaseEntity::PACKAGE_NAME . '.user_class', FakeUserReferableClass::class );
        Config::set(LaravelBaseEntity::PACKAGE_NAME . '.system_user_id', $fake_system_user_id );

        // WHEN LaravelBaseEntity is trying to get the current user id
        FakeUserReferableClass::$canGetUserUserObj = false;
        $entity = new LaravelBaseEntity();
        $currentUserId = $entity->getCurrentUserId();

        // THEN the user id will be returned.
        $this->assertEquals($fake_system_user_id, $currentUserId);
    }
}