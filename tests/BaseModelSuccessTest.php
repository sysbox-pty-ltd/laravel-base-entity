<?php

namespace Sysbox\LaravelBaseEntity\Tests;
/**
 * Created by PhpStorm.
 * User: helin16
 * Date: 2020-05-05
 * Time: 19:55
 */

use Sysbox\LaravelBaseEntity\LaravelBaseEntity;
use Tests\TestCase;
use Sysbox\LaravelBaseEntity\BaseModel;


class BaseModelHelper extends BaseModel {
    public function getNeedUUID() {
        return $this->needUUID;
    }
    public function getKeyType() {
        return $this->keyType;
    }
    public function getDates() {
        return $this->dates;
    }
}


class BaseModelSuccessTest extends TestCase
{
    /**
     * GIVEN a BaseModel Class
     * WHEN it's been initiated
     * THEN default value will be created with the class.
     *
     * @test
     */
    public function aBaseModelCanBeCreatedWitDefaultValue() {
        // GIVEN a BaseModel Class

        // WHEN it's been initiated
        $model = new BaseModelHelper();

        // THEN default value will be created with the class.
        $this->assertFalse($model->incrementing);
        $this->assertTrue($model->getNeedUUID());
        $this->assertEquals('string', $model->getKeyType());
        $this->assertEquals(['created_at', 'updated_at'], $model->getDates());
        var_dump($this->app['config']->get('laravelBaseEntity.user_class'));
    }
    /**
     * GIVEN a BaseModel Class
     * WHEN it's been initiated statically
     * THEN default value will be created with the class.
     *
     * @test
     */
    public function aBaseModelCanBeUsedInitiatedStaticallyWithDefaultValue() {
        // GIVEN a BaseModel Class

        // WHEN it's been initiated statically

        // THEN default value will be created with the class.
        $this->assertFalse(BaseModel::$byPassObserver);
        $this->assertFalse(BaseModelHelper::$byPassObserver);
    }
    /**
     * GIVEN a BaseModel object
     * WHEN it's been activated
     * THEN it will be marked as activated.
     *
     * @test
     */
    public function aBaseModelCanBeUsedActivated() {
        // GIVEN a BaseModel object
        $model = new BaseModelHelper();

        // WHEN it's been activated
        $actual = $model->activate();

        // THEN it will be marked as activated.
        $this->assertEquals(1, $actual->active);
        $this->assertEquals(1, $model->active);
        $this->assertTrue($actual->isActive());
        $this->assertTrue($model->isActive());
    }

    /**
     * GIVEN a BaseModel object
     * WHEN it's been deactivated
     * THEN it will be marked as deactivated.
     *
     * @test
     */
    public function aBaseModelCanBeUsedDeactivated() {
        // GIVEN a BaseModel object
        $model = new BaseModelHelper();

        // WHEN it's been activated
        $actual = $model->deactivate();

        // THEN it will be marked as activated.
        $this->assertEquals(0, $actual->active);
        $this->assertEquals(0, $model->active);
        $this->assertFalse($actual->isActive());
        $this->assertFalse($model->isActive());
    }
    /**
     * GIVEN a BaseModel class and $byPassObserver is true
     * WHEN this BaseModel is Called
     * THEN it will by pass the observer.
     *
     * @test
     */
    public function aBaseModelWillByPassObserverWhenFlagIsSetToBeTrue() {
        // GIVEN a BaseModel class and $byPassObserver is true
        BaseModelHelper::$byPassObserver = true;
        BaseModel::$byPassObserver = true;

        // WHEN this BaseModel is Called
        \Mockery::mock(BaseModelHelper::class)->makePartial()->shouldReceive('observe')->never();
        BaseModelHelper::boot();
        \Mockery::mock(BaseModel::class)->makePartial()->shouldReceive('observe')->never();
        BaseModel::boot();
    }
    /**
     * GIVEN a BaseModel class and $byPassObserver is NOT true
     * WHEN this BaseModel is Called
     * THEN it will by pass the observer.
     *
     */
    public function aBaseModelWillNotByPassObserverWhenFlagIsSetToBeNotTrue() {
        // GIVEN a BaseModel class and $byPassObserver is true
        BaseModel::$byPassObserver = true;
        BaseModelHelper::$byPassObserver = false;

        // WHEN this BaseModel is Called
        \Mockery::mock(LaravelBaseEntity::class)->shouldReceive('boot')->withNoArgs()->once();
        $mock = \Mockery::mock(BaseModelHelper::class)->makePartial();
        $mock->shouldReceive('observe')
            ->once()->withAnyArgs();
//            ->with(
//                \Mockery::on(function($param) {
//                    var_dump($param);
//                    return true;
//                })
//            );
        BaseModelHelper::boot();
    }
}