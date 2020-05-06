<?php
/**
 * Created by PhpStorm.
 * User: helin16
 * Date: 2020-05-06
 * Time: 16:51
 */

namespace Sysbox\LaravelBaseEntity\Interfaces;


interface UserReferable
{
    /**
     * getting the user id for the created_by_id or updated_by_id
     * @return mixed
     */
    public function getUserId();
}