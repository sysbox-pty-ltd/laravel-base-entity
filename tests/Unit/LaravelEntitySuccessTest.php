<?php

namespace Sysbox\LaravelBaseEntity {
    const RANDOM_INT_FOR_TESTING = 12121201982;
    /**
     * Override random_int() in the My\Namespace namespace when testing
     *
     * @return int
     */
    function random_int($min, $max)
    {
        return RANDOM_INT_FOR_TESTING;// return your mock or whatever value you want to use for testing
    }
}

namespace Sysbox\LaravelBaseEntity\Tests\Unit {
    /**
     * Created by PhpStorm.
     * User: helin16
     * Date: 2020-05-05
     * Time: 19:55
     */

    use Carbon\Carbon;
    use Illuminate\Support\Facades\Config;
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
            Config::set('laravelBaseEntity.' . $field, $value);

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

            Carbon::setTestNow(Carbon::createFromDate(2020, 1, 1));
            $expected = md5(implode('_', [LaravelBaseEntity::class, null, Carbon::now()->getTimestamp(), RANDOM_INT_FOR_TESTING]));
            $entity = new LaravelBaseEntity();

            $this->assertEquals($expected, $entity->getSystemUserId());
        }
    }
}