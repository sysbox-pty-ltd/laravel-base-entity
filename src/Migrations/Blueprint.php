<?php
/**
 * Created by PhpStorm.
 * User: helin16
 * Date: 2020-05-07
 * Time: 11:34
 */

namespace Sysbox\LaravelBaseEntity\Migrations;


class Blueprint extends \Illuminate\Database\Schema\Blueprint
{
    /**
     * @param string $column
     * @return $this|\Illuminate\Database\Schema\ColumnDefinition
     */
    public function id($column = 'id')
    {
        $this->hashId($column)->index();
        $this->primary($column);
        return $this;
    }

    /**
     * Adding a hash id column to the table
     *
     * @param $name
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function hashId($name)
    {
        return $this->string($name, 32);
    }

    /**
     * Add creation and update timestamps to the table.
     *
     * @return void
     */
    public function basicLaravelBaseEntityColumns()
    {
        $this->id();
        $this->boolean('active')->index();
        $this->hashId('created_by_id')->index();
        $this->timestamp('created_at')->nullable();
        $this->hashId('updated_by_id')->index();
        $this->timestamp('updated_at')->nullable();
    }
}