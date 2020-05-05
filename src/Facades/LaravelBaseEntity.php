<?php
namespace Sysbox\LaravelBaseEntity\Facades;
/**
 * Created by PhpStorm.
 * User: helin16
 * Date: 2020-05-05
 * Time: 21:24
 */
use \Illuminate\Support\Facades\Facade;

class LaravelBaseEntity extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'mypackage';
    }
}