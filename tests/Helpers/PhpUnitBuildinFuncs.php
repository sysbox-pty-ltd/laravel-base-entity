<?php
/**
 * Created by PhpStorm.
 * User: helin16
 * Date: 2020-05-06
 * Time: 15:44
 */
namespace Sysbox\LaravelBaseEntity;


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

