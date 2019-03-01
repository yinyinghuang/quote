<?php
namespace Api\Test\TestCase\Model\Table;

use Api\Model\Table\CategoryAttributeFiltersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * Api\Model\Table\CategoryAttributeFiltersTable Test Case
 */
class CategoryAttributeFiltersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \Api\Model\Table\CategoryAttributeFiltersTable
     */
    public $CategoryAttributeFilters;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.Api.CategoryAttributeFilters',
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
        $config = TableRegistry::getTableLocator()->exists('CategoryAttributeFilters') ? [] : ['className' => CategoryAttributeFiltersTable::class];
        $this->CategoryAttributeFilters = TableRegistry::getTableLocator()->get('CategoryAttributeFilters', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CategoryAttributeFilters);

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
