<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MerchantLocationsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MerchantLocationsTable Test Case
 */
class MerchantLocationsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\MerchantLocationsTable
     */
    public $MerchantLocations;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.MerchantLocations',
        'app.Merchants',
        'app.Districts'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('MerchantLocations') ? [] : ['className' => MerchantLocationsTable::class];
        $this->MerchantLocations = TableRegistry::getTableLocator()->get('MerchantLocations', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->MerchantLocations);

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
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
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
