<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CategoryAttributeFiltersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CategoryAttributeFiltersTable Test Case
 */
class CategoryAttributeFiltersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CategoryAttributeFiltersTable
     */
    public $CategoryAttributeFilters;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.CategoryAttributeFilters',
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
