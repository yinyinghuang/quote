<?php
namespace Api\Test\TestCase\Model\Table;

use Api\Model\Table\AttributesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * Api\Model\Table\AttributesTable Test Case
 */
class AttributesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \Api\Model\Table\AttributesTable
     */
    public $Attributes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.Api.Attributes',
        'plugin.Api.Categories',
        'plugin.Api.Products'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Attributes') ? [] : ['className' => AttributesTable::class];
        $this->Attributes = TableRegistry::getTableLocator()->get('Attributes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Attributes);

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
