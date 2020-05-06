<?php
/**
 * Created by PhpStorm.
 * User: helin16
 * Date: 2020-05-06
 * Time: 18:34
 */

namespace Sysbox\LaravelBaseEntity\Helpers;

use Sysbox\LaravelBaseEntity\BaseModel;

class FakeBaseModelClass extends BaseModel {

    const TABLE_NAME = 'fake_table_name';
    public $table = self::TABLE_NAME;
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