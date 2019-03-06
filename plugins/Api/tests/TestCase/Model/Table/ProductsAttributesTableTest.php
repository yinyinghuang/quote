<?php
namespace Api\Test\TestCase\Model\Table;

use Api\Model\Table\ProductsAttributesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * Api\Model\Table\ProductsAttributesTable Test Case
 */
class ProductsAttributesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \Api\Model\Table\ProductsAttributesTable
     */
    public $ProductsAttributes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.Api.ProductsAttributes',
        'plugin.Api.Products',
        'plugin.Api.CategoryAttributes'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ProductsAttributes') ? [] : ['className' => ProductsAttributesTable::class];
        $this->ProductsAttributes = TableRegistry::getTableLocator()->get('ProductsAttributes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProductsAttributes);

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
