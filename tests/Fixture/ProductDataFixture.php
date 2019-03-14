<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ProductDataFixture
 *
 */
class ProductDataFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'product_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '产品ID', 'precision' => null, 'autoIncrement' => null],
        'view_count' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => '0', 'comment' => '浏览次数', 'precision' => null, 'autoIncrement' => null],
        'collect_count' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => '0', 'comment' => '收藏次数', 'precision' => null, 'autoIncrement' => null],
        'comment_count' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => '0', 'comment' => '评论次数', 'precision' => null, 'autoIncrement' => null],
        'quote_count' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '报价条数', 'precision' => null, 'autoIncrement' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['product_id'], 'length' => []],
            'product_id' => ['type' => 'unique', 'columns' => ['product_id'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'MyISAM',
            'collation' => 'utf8mb4_general_ci'
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
                'product_id' => 1,
                'view_count' => 1,
                'collect_count' => 1,
                'comment_count' => 1,
                'quote_count' => 1
            ],
        ];
        parent::init();
    }
}
