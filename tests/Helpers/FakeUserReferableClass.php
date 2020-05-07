<?php
/**
 * Created by PhpStorm.
 * User: helin16
 * Date: 2020-05-07
 * Time: 11:13
 */

namespace Sysbox\LaravelBaseEntity\Helpers;


use Sysbox\LaravelBaseEntity\Interfaces\UserReferable;

class FakeUserReferableClass implements UserReferable
{
    public static $currentUserId = 'fake_user_id';
    public static $canGetUserUserObj = true;

    /**
     * @return mixed|void
     */
    public function getUserId()
    {
        return self::$currentUserId;
    }

    /**
     * @return UserReferable|void|null
     */
    public static function getCurrentUser()
    {
        if(self::$canGetUserUserObj === true) {
            return new self();
        }
        return null;
    }
}