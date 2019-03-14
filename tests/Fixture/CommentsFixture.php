<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CommentsFixture
 *
 */
class CommentsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '评论ID', 'precision' => null, 'autoIncrement' => null],
        'product_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '产品ID', 'precision' => null, 'autoIncrement' => null],
        'fan_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '粉丝ID', 'precision' => null, 'autoIncrement' => null],
        'rating' => ['type' => 'integer', 'length' => 100, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '评级', 'precision' => null, 'autoIncrement' => null],
        'content' => ['type' => 'string', 'length' => 5000, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_general_ci', 'comment' => '评论内容', 'precision' => null, 'fixed' => null],
        'is_checked' => ['type' => 'tinyinteger', 'length' => 2, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '是否审核通过', 'precision' => null],
        'sort' => ['type' => 'smallinteger', 'length' => 6, 'unsigned' => false, 'null' => true, 'default' => '0', 'comment' => '排序', 'precision' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '创建时间', 'precision' => null],
        '_indexes' => [
            'product_key' => ['type' => 'index', 'columns' => ['product_id'], 'length' => []],
            'fan_key' => ['type' => 'index', 'columns' => ['fan_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
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
                'id' => 1,
                'product_id' => 1,
                'fan_id' => 1,
                'rating' => 1,
                'content' => 'Lorem ipsum dolor sit amet',
                'is_checked' => 1,
                'sort' => 1,
                'created' => '2019-03-14 01:54:06'
            ],
        ];
        parent::init();
    }
}
