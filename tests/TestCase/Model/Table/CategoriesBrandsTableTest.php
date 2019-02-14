<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CategoriesBrandsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CategoriesBrandsTable Test Case
 */
class CategoriesBrandsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CategoriesBrandsTable
     */
    public $CategoriesBrands;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.CategoriesBrands',
        'app.Categories'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('CategoriesBrands') ? [] : ['className' => CategoriesBrandsTable::class];
        $this->CategoriesBrands = TableRegistry::getTableLocator()->get('CategoriesBrands', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CategoriesBrands);

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
