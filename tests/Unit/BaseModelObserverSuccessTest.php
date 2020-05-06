<?php

namespace Sysbox\LaravelBaseEntity\Tests\Unit;
/**
 * Created by PhpStorm.
 * User: helin16
 * Date: 2020-05-05
 * Time: 19:55
 */

use Carbon\Carbon;
use Tests\TestCase;
use Sysbox\LaravelBaseEntity\BaseModelObserver;
use Sysbox\LaravelBaseEntity\Facades\LaravelBaseEntity;
use Sysbox\LaravelBaseEntity\Helpers\FakeBaseModelClass;


class BaseModelObserverSuccessTest extends TestCase
{
    /**
     * @test
     */
    public function aBaseModelObserverCanUpdateFieldsForABaseModel() {

        $observer = new BaseModelObserver();
        LaravelBaseEntity::shouldReceive('bootModel')->withNoArgs()->once();
        $fakeModel = new FakeBaseModelClass();

        $fakeUpdatedById = 'fake_updated_by_id';
        $fakeUpdatedAt = Carbon::createFromDate(2020, 1, 1);
        LaravelBaseEntity::shouldReceive('getCurrentUserId')->withNoArgs()->once()->andReturn($fakeUpdatedById);
        LaravelBaseEntity::shouldReceive('getNow')->withNoArgs()->once()->andReturn($fakeUpdatedAt);
        $observer->updating($fakeModel);

        $this->assertEquals($fakeUpdatedById, $fakeModel->updated_by_id);
        $this->assertEquals(trim($fakeUpdatedAt), trim($fakeModel->updated_at));
    }

    /**
     * @test
     */
    public function aBaseModelObserverCanCreateFieldsForABaseModel() {
        $observer = new BaseModelObserver();
        LaravelBaseEntity::shouldReceive('bootModel')->withNoArgs()->once();
        $fakeModel = new FakeBaseModelClass();

        $fakeId = 'fake_model_id';
        $fakeUserId = 'fake_user_id';
        $fakeNow = Carbon::createFromDate(2020, 1, 1);
        LaravelBaseEntity::shouldReceive('genHashId')->withArgs([get_class($fakeModel), $fakeUserId, $fakeNow])->once()->andReturn($fakeId);
        LaravelBaseEntity::shouldReceive('getCurrentUserId')->withNoArgs()->once()->andReturn($fakeUserId);
        LaravelBaseEntity::shouldReceive('getNow')->withNoArgs()->once()->andReturn($fakeNow);
        $observer->creating($fakeModel);

        $this->assertEquals($fakeId, $fakeModel->id);
        $this->assertEquals(1, $fakeModel->active);
        $this->assertEquals($fakeUserId, $fakeModel->created_by_id);
        $this->assertEquals($fakeUserId, $fakeModel->updated_by_id);
        $this->assertEquals(trim($fakeNow), trim($fakeModel->created_at));
        $this->assertEquals(trim($fakeNow), trim($fakeModel->updated_at));
    }
}