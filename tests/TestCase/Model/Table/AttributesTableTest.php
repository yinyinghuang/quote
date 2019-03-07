<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AttributesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AttributesTable Test Case
 */
class AttributesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\AttributesTable
     */
    public $Attributes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Attributes',
        'app.Categories',
        'app.Products'
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
