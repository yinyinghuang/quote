<?php
namespace Api\Test\TestCase\Model\Table;

use Api\Model\Table\ProductsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * Api\Model\Table\ProductsTable Test Case
 */
class ProductsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \Api\Model\Table\ProductsTable
     */
    public $Products;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.Api.Products',
        'plugin.Api.Zones',
        'plugin.Api.Groups',
        'plugin.Api.Categories',
        'plugin.Api.Comments',
        'plugin.Api.Quotes',
        'plugin.Api.Attributes'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Products') ? [] : ['className' => ProductsTable::class];
        $this->Products = TableRegistry::getTableLocator()->get('Products', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Products);

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
