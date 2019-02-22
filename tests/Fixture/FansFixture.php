<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * FansFixture
 *
 */
class FansFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '粉丝ID', 'precision' => null, 'autoIncrement' => null],
        'openId' => ['type' => 'string', 'length' => 50, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_general_ci', 'comment' => 'openID', 'precision' => null, 'fixed' => null],
        'nickName' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_general_ci', 'comment' => '用户昵称', 'precision' => null, 'fixed' => null],
        'avatarUrl' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_general_ci', 'comment' => '用户头像', 'precision' => null, 'fixed' => null],
        'gender' => ['type' => 'tinyinteger', 'length' => 2, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '用户的性别', 'precision' => null],
        'city' => ['type' => 'string', 'length' => 50, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_general_ci', 'comment' => '用户所在城市', 'precision' => null, 'fixed' => null],
        'province' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_general_ci', 'comment' => '用户所在省份', 'precision' => null, 'fixed' => null],
        'country' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_general_ci', 'comment' => '用户所在国家', 'precision' => null, 'fixed' => null],
        'language' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_general_ci', 'comment' => '用户的语言', 'precision' => null, 'fixed' => null],
        'sign_up' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '注册时间', 'precision' => null],
        'last_access' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '上次访问时间', 'precision' => null],
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
                'openId' => 'Lorem ipsum dolor sit amet',
                'nickName' => 'Lorem ipsum dolor sit amet',
                'avatarUrl' => 'Lorem ipsum dolor sit amet',
                'gender' => 1,
                'city' => 'Lorem ipsum dolor sit amet',
                'province' => 'Lorem ipsum dolor sit amet',
                'country' => 'Lorem ipsum dolor sit amet',
                'language' => 'Lorem ipsum dolor sit amet',
                'sign_up' => '2019-02-21 09:35:31',
                'last_access' => '2019-02-21 09:35:31'
            ],
        ];
        parent::init();
    }
}
