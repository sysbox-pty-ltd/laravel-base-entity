<?php

namespace Sysbox\LaravelBaseEntity\Tests\Unit;
/**
 * Created by PhpStorm.
 * User: helin16
 * Date: 2020-05-05
 * Time: 19:55
 */

use Illuminate\Database\Eloquent\Model;
use Sysbox\LaravelBaseEntity\Facades\LaravelBaseEntity;
use Tests\TestCase;
use Sysbox\LaravelBaseEntity\BaseModel;
use Sysbox\LaravelBaseEntity\Helpers\FakeBaseModelClass;

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
        LaravelBaseEntity::shouldReceive('bootModel')->withNoArgs()->once();
        $model = new FakeBaseModelClass();

        // THEN default value will be created with the class.
        $this->assertInstanceOf(BaseModel::class, $model);
        $this->assertInstanceOf(Model::class, $model);
        $this->assertFalse($model->incrementing);
        $this->assertTrue($model->getNeedUUID());
        $this->assertEquals('string', $model->getKeyType());
        $this->assertEquals(['created_at', 'updated_at'], $model->getDates());
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
        $this->assertFalse(FakeBaseModelClass::$byPassObserver);
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
        LaravelBaseEntity::shouldReceive('bootModel')->withNoArgs()->once();
        $model = new FakeBaseModelClass();

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
        LaravelBaseEntity::shouldReceive('bootModel')->withNoArgs()->once();
        $model = new FakeBaseModelClass();

        // WHEN it's been activated
        $actual = $model->deactivate();

        // THEN it will be marked as activated.
        $this->assertEquals(0, $actual->active);
        $this->assertEquals(0, $model->active);
        $this->assertFalse($actual->isActive());
        $this->assertFalse($model->isActive());
    }

    /**
     * GIVEN a BaseModel entity
     * WHEN it's been initiated
     * THEN it should contain provided scope functions.
     *
     * @test
     * @dataProvider dataProviderForaBaseModelNeedToHaveScopeFunctions
     */
    public function aBaseModelNeedToHaveScopeFunctions($scopeFuncName, $queryFuncName, $scopeParam, $mockParams) {
        // GIVEN a BaseModel entity

        // WHEN it's been initiated
        LaravelBaseEntity::shouldReceive('bootModel')->withNoArgs()->once();
        $model = new FakeBaseModelClass();

        // THEN it should contain provided scope functions.
        $fake_return = 'fake_result';
        $mockQuery = \Mockery::mock('fakeQueryClass');
        $mockQuery->shouldReceive($queryFuncName)
            ->withArgs($mockParams)
            ->once()
            ->andReturn($fake_return);
        $this->assertEquals($fake_return, $model->$scopeFuncName($mockQuery, $scopeParam));
    }

    /**
     * data provider for aBaseModelNeedToHaveScopeFunctions
     */
    public function dataProviderForaBaseModelNeedToHaveScopeFunctions() {
        $return = [];
        // scopeOfActive
        foreach([1, 0, '1', '0', null, ''] as $value) {
            $return[] = ['scopeOfActive', 'where', $value, [FakeBaseModelClass::TABLE_NAME . '.active', intval($value)]];
        }
        // scopeOfId
        $return[] = ['scopeOfId', 'where', 'fake_id', [FakeBaseModelClass::TABLE_NAME . '.id', 'fake_id']];
        // scopeOfIds
        $return[] = ['scopeOfIds', 'whereIn', ['fake_ids'], [FakeBaseModelClass::TABLE_NAME . '.id', ['fake_ids']]];
        // scopeOfNotId
        $return[] = ['scopeOfNotId', 'where', 'fake_ids', [FakeBaseModelClass::TABLE_NAME . '.id', '!=', 'fake_ids']];
        // scopeOfNotIds
        $return[] = ['scopeOfNotIds', 'whereNotIn', ['fake_ids'], [FakeBaseModelClass::TABLE_NAME . '.id', ['fake_ids']]];

        foreach(['created_at', 'updated_at'] as $field) {
            foreach(['>', '=', '<', '>=', '<='] as $operator) {
                $funcName = 'scopeOf' . implode('', array_map(function($field) {
                    return ucfirst($field);
                }, explode('_', $field)));
                switch($operator) {
                    case '>': { $funcName .= 'NewerThan'; break;}
                    case '<': { $funcName .= 'OlderThan'; break;}
                    case '>=': { $funcName .= 'NewerAndEqualTo'; break;}
                    case '<=': { $funcName .= 'OlderAndEqualTo'; break;}
                    default: {break;}
                }
                $return[] = [$funcName, 'where', 'fake_date', [FakeBaseModelClass::TABLE_NAME . '.' . $field, $operator, 'fake_date']];
            }
        }
        return $return;
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
        FakeBaseModelClass::$byPassObserver = true;

        // WHEN this BaseModel is Called
        LaravelBaseEntity::shouldReceive('bootModel')->withNoArgs()->once();
        \Mockery::mock(FakeBaseModelClass::class)->makePartial()->shouldReceive('observe')->never();
        FakeBaseModelClass::boot();
        $this->assertTrue(true);
    }
    /**
     * GIVEN a BaseModel class and $byPassObserver is NOT true
     * WHEN this BaseModel is Called
     * THEN it will by pass the observer.
     *
     * @test
     */
    public function aBaseModelWillNotByPassObserverWhenFlagIsSetToBeNotTrue() {
        // GIVEN a BaseModel class and $byPassObserver is true
        BaseModel::$byPassObserver = true;
        FakeBaseModelClass::$byPassObserver = false;

        // WHEN this BaseModel is Called
        LaravelBaseEntity::shouldReceive('bootModel')->withNoArgs()->once();
        $mock = \Mockery::mock(FakeBaseModelClass::class)->makePartial();
        $mock->shouldReceive('observe')
            ->once()->withAnyArgs();
//            ->with(
//                \Mockery::on(function($param) {
//                    var_dump($param);
//                    return true;
//                })
//            );
        FakeBaseModelClass::boot();
    }
}