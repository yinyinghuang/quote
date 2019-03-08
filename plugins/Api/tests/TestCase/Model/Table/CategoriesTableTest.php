<?php
namespace Api\Test\TestCase\Model\Table;

use Api\Model\Table\CategoriesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * Api\Model\Table\CategoriesTable Test Case
 */
class CategoriesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \Api\Model\Table\CategoriesTable
     */
    public $Categories;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.Api.Categories',
        'plugin.Api.Zones',
        'plugin.Api.Groups',
        'plugin.Api.Products',
        'plugin.Api.Attributes',
        'plugin.Api.Brands'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Categories') ? [] : ['className' => CategoriesTable::class];
        $this->Categories = TableRegistry::getTableLocator()->get('Categories', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Categories);

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
