<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * MerchantLocationsFixture
 *
 */
class MerchantLocationsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '商户地址ID', 'autoIncrement' => true, 'precision' => null],
        'pid' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => 'price.com.hk.id', 'precision' => null, 'autoIncrement' => null],
        'merchant_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '商户ID', 'precision' => null, 'autoIncrement' => null],
        'district_id' => ['type' => 'tinyinteger', 'length' => 7, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '地区ID', 'precision' => null],
        'openhour' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '营业时间', 'precision' => null, 'fixed' => null],
        'contact' => ['type' => 'string', 'length' => 50, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '电话', 'precision' => null, 'fixed' => null],
        'address' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '地址', 'precision' => null, 'fixed' => null],
        'latitude' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '维度', 'precision' => null, 'fixed' => null],
        'longtitude' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '经度', 'precision' => null, 'fixed' => null],
        'sort' => ['type' => 'smallinteger', 'length' => 6, 'unsigned' => false, 'null' => true, 'default' => '0', 'comment' => '排序', 'precision' => null],
        'created' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => 'CURRENT_TIMESTAMP', 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => '0000-00-00 00:00:00', 'comment' => '', 'precision' => null],
        '_indexes' => [
            'district_key' => ['type' => 'index', 'columns' => ['district_id'], 'length' => []],
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
                'merchant_id' => 1,
                'district_id' => 1,
                'openhour' => 'Lorem ipsum dolor sit amet',
                'contact' => 'Lorem ipsum dolor sit amet',
                'address' => 'Lorem ipsum dolor sit amet',
                'latitude' => 'Lorem ipsum dolor sit amet',
                'longtitude' => 'Lorem ipsum dolor sit amet',
                'sort' => 1,
                'created' => 1548120701,
                'modified' => 1548120701
            ],
        ];
        parent::init();
    }
}
