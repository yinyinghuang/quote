<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CategoriesAttributesFixture
 *
 */
class CategoriesAttributesFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '分类属性ID', 'autoIncrement' => true, 'precision' => null],
        'category_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '分类ID', 'precision' => null, 'autoIncrement' => null],
        'attribute_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '属性ID', 'precision' => null, 'autoIncrement' => null],
        'level' => ['type' => 'tinyinteger', 'length' => 2, 'unsigned' => true, 'null' => false, 'default' => '0', 'comment' => '属性ID', 'precision' => null],
        'unit' => ['type' => 'string', 'length' => 10, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '属性单位', 'precision' => null, 'fixed' => null],
        'is_filter' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '0', 'comment' => '是否为筛选项。0：否；1：是', 'precision' => null],
        'filter_type' => ['type' => 'tinyinteger', 'length' => 2, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '筛选项类型。0：非筛选项；1：单选；2：多选', 'precision' => null],
        'sort' => ['type' => 'smallinteger', 'length' => 6, 'unsigned' => false, 'null' => true, 'default' => '0', 'comment' => '排序', 'precision' => null],
        '_indexes' => [
            'attribute_key' => ['type' => 'index', 'columns' => ['attribute_id'], 'length' => []],
            'category_key' => ['type' => 'index', 'columns' => ['category_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
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
                'category_id' => 1,
                'attribute_id' => 1,
                'level' => 1,
                'unit' => 'Lorem ip',
                'is_filter' => 1,
                'filter_type' => 1,
                'sort' => 1
            ],
        ];
        parent::init();
    }
}
