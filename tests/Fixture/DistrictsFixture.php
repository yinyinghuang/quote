<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * DistrictsFixture
 *
 */
class DistrictsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'pid' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => 'price.com.hk.id', 'precision' => null, 'autoIncrement' => null],
        'area_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'name' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'sort' => ['type' => 'smallinteger', 'length' => 6, 'unsigned' => false, 'null' => true, 'default' => '0', 'comment' => '', 'precision' => null],
        '_indexes' => [
            'area_key' => ['type' => 'index', 'columns' => ['area_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'p_id' => ['type' => 'unique', 'columns' => ['pid'], 'length' => []],
            'name' => ['type' => 'unique', 'columns' => ['name'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'MyISAM',
            'collation' => 'utf8_general_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Init method
     *
     * @return void
     */
    public function init()
    {
        $this->records = [
            [
                'id' => 1,
                'pid' => 1,
                'area_id' => 1,
                'name' => 'Lorem ipsum dolor sit amet',
                'sort' => 1
            ],
        ];
        parent::init();
    }
}
