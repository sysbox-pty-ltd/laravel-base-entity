<?php
/**
 * Created by PhpStorm.
 * User: helin16
 * Date: 2020-05-07
 * Time: 11:40
 */

use Tests\TestCase;
use Sysbox\LaravelBaseEntity\Migrations\Blueprint;

/**
 * Class BlueprintTest
 */
class BlueprintTest extends TestCase
{
    /**
     * @test
     */
    public function blueprintCanAddHashIdColumnAsId() {
        $blueprint = new Blueprint('fake_table');

        $blueprint->id();
        $columns = $blueprint->getColumns();

        $this->assertEquals([
            'type' => 'string',
            'name' => 'id',
            'length' => 32,
            'index' => true,
        ], $columns[0]->getAttributes());
    }

    /**
     * @test
     */
    public function blueprintCanAddHashIdColumnAsDifferentName() {
        $blueprint = new Blueprint('fake_table');

        $columnName = 'fake_column_name';
        $blueprint->hashId($columnName);
        $columns = $blueprint->getColumns();

        $this->assertEquals([
            'type' => 'string',
            'name' => $columnName,
            'length' => 32,
        ], $columns[0]->getAttributes());
    }

    /**
     * @test
     */
    public function blueprintCanAddBasicLaravelBaseEntityColumns() {
        $blueprint = new Blueprint('fake_table');

        $blueprint->basicLaravelBaseEntityColumns();
        $columns = $blueprint->getColumns();

        $this->assertEquals([
            'type' => 'string',
            'name' => 'id',
            'length' => 32,
            'index' => true,
        ], $columns[0]->getAttributes());
        $this->assertEquals([
            'type' => 'boolean',
            'name' => 'active',
            'index' => true,
        ], $columns[1]->getAttributes());
        $this->assertEquals([
            'type' => 'string',
            'name' => 'created_by_id',
            'length' => 32,
            'index' => true,
        ], $columns[2]->getAttributes());
        $this->assertEquals([
            'type' => 'timestamp',
            'name' => 'created_at',
            'precision' => 0,
            'nullable' => true,
        ], $columns[3]->getAttributes());
        $this->assertEquals([
            'type' => 'string',
            'name' => 'updated_by_id',
            'length' => 32,
            'index' => true,
        ], $columns[4]->getAttributes());
        $this->assertEquals([
            'type' => 'timestamp',
            'name' => 'updated_at',
            'precision' => 0,
            'nullable' => true,
        ], $columns[5]->getAttributes());
    }
}