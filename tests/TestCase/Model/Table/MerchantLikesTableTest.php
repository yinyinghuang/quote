<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MerchantLikesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MerchantLikesTable Test Case
 */
class MerchantLikesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\MerchantLikesTable
     */
    public $MerchantLikes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.MerchantLikes',
        'app.Merchants',
        'app.Fans'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('MerchantLikes') ? [] : ['className' => MerchantLikesTable::class];
        $this->MerchantLikes = TableRegistry::getTableLocator()->get('MerchantLikes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->MerchantLikes);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
