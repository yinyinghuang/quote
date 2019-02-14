<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CategoriesFixture
 *
 */
class CategoriesFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '分类ID', 'autoIncrement' => true, 'precision' => null],
        'pid' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => 'price.com.hk.id', 'precision' => null, 'autoIncrement' => null],
        'group_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '分组ID', 'precision' => null, 'autoIncrement' => null],
        'name' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8_bin', 'comment' => '分类名', 'precision' => null, 'fixed' => null],
        'is_visible' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '1', 'comment' => '前端是否显示。0：不显示；1：显示', 'precision' => null],
        'sort' => ['type' => 'smallinteger', 'length' => 6, 'unsigned' => false, 'null' => true, 'default' => '0', 'comment' => '排序', 'precision' => null],
        'created' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => 'CURRENT_TIMESTAMP', 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'group_key' => ['type' => 'index', 'columns' => ['group_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'p_id' => ['type' => 'unique', 'columns' => ['pid'], 'length' => []],
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
                'group_id' => 1,
                'name' => 'Lorem ipsum dolor sit amet',
                'is_visible' => 1,
                'sort' => 1,
                'created' => 1548120699,
                'modified' => 1548120699
            ],
        ];
        parent::init();
    }
}
