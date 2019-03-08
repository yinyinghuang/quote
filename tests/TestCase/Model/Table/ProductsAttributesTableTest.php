<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProductsAttributesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProductsAttributesTable Test Case
 */
class ProductsAttributesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ProductsAttributesTable
     */
    public $ProductsAttributes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ProductsAttributes',
        'app.Products',
        'app.CategoryAttributes'
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
