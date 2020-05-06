<?php

namespace Sysbox\LaravelBaseEntity\Tests\Unit;
/**
 * Created by PhpStorm.
 * User: helin16
 * Date: 2020-05-05
 * Time: 19:55
 */

use Sysbox\LaravelBaseEntity\Exception;
use PHPUnit\Framework\TestCase;


class ExceptionTest extends TestCase
{
    /**
     * GIVEN a Exception Class
     * WHEN it's been initiated
     * THEN it should be an \Exception
     *
     * @test
     */
    public function aExceptionShouldBeABaseException() {
        // GIVEN a Exception Class

        // WHEN it's been initiated
        $exception = new Exception();

        // it should be an \Exception
        $this->assertInstanceOf(\Exception::class, $exception);
    }
}