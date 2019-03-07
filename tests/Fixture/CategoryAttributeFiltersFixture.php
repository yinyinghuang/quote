<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CategoryAttributeFiltersFixture
 *
 */
class CategoryAttributeFiltersFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '筛选项内容ID', 'autoIncrement' => true, 'precision' => null],
        'pid' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => 'price.com.hk.id', 'precision' => null, 'autoIncrement' => null],
        'category_attribute_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '分类属性ID', 'precision' => null, 'autoIncrement' => null],
        'filter' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '筛选项内容', 'precision' => null, 'fixed' => null],
        'is_visible' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '1', 'comment' => '前端是否显示。0：不显示；1：显示', 'precision' => null],
        'sort' => ['type' => 'smallinteger', 'length' => 6, 'unsigned' => false, 'null' => true, 'default' => '0', 'comment' => '排序', 'precision' => null],
        '_indexes' => [
            'category_attribute_key' => ['type' => 'index', 'columns' => ['category_attribute_id'], 'length' => []],
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
                'category_attribute_id' => 1,
                'filter' => 'Lorem ipsum dolor sit amet',
                'is_visible' => 1,
                'sort' => 1
            ],
        ];
        parent::init();
    }
}
