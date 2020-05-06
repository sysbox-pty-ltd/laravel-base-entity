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
}