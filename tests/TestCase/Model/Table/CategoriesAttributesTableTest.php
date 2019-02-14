<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CategoriesAttributesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CategoriesAttributesTable Test Case
 */
class CategoriesAttributesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CategoriesAttributesTable
     */
    public $CategoriesAttributes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.CategoriesAttributes',
        'app.Categories',
        'app.Attributes'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('CategoriesAttributes') ? [] : ['className' => CategoriesAttributesTable::class];
        $this->CategoriesAttributes = TableRegistry::getTableLocator()->get('CategoriesAttributes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CategoriesAttributes);

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
