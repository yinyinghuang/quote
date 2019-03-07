<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CategoriesBrandsFixture
 *
 */
class CategoriesBrandsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'category_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '分类ID', 'precision' => null, 'autoIncrement' => null],
        'brand' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8_bin', 'comment' => '品牌名', 'precision' => null, 'fixed' => null],
        'created' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => 'CURRENT_TIMESTAMP', 'comment' => '', 'precision' => null],
        '_indexes' => [
            'brand_key' => ['type' => 'index', 'columns' => ['brand'], 'length' => []],
            'category_key' => ['type' => 'index', 'columns' => ['category_id'], 'length' => []],
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
                'category_id' => 1,
                'brand' => 'Lorem ipsum dolor sit amet',
                'created' => 1548120700
            ],
        ];
        parent::init();
    }
}
