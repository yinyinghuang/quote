<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * MerchantLikesFixture
 *
 */
class MerchantLikesFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'merchant_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '商户ID', 'precision' => null, 'autoIncrement' => null],
        'fan_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '粉丝ID', 'precision' => null, 'autoIncrement' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '创建时间', 'precision' => null],
        '_indexes' => [
            'merchant_key' => ['type' => 'index', 'columns' => ['merchant_id'], 'length' => []],
            'fan_key' => ['type' => 'index', 'columns' => ['fan_id'], 'length' => []],
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
                'merchant_id' => 1,
                'fan_id' => 1,
                'created' => '2019-03-21 01:39:26'
            ],
        ];
        parent::init();
    }
}
