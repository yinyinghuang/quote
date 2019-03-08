<?php
namespace Api\Test\TestCase\Model\Table;

use Api\Model\Table\FansTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * Api\Model\Table\FansTable Test Case
 */
class FansTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \Api\Model\Table\FansTable
     */
    public $Fans;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.Api.Fans',
        'plugin.Api.Comments'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Fans') ? [] : ['className' => FansTable::class];
        $this->Fans = TableRegistry::getTableLocator()->get('Fans', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Fans);

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
}
