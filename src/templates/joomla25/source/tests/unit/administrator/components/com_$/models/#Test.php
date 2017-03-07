<?template scope="entity"?>
<?php
/**
 * {{ project.title }} {{ entity.name | title }} Admin Item Model Tests
 *
 * PHP version 5.3
 *
{# align("\t", '  ') #}
 * @version    {{ project.version }}
 * @package    {{ project.name | class }}.UnitTest
 * @author     {{ author.name }} <{{ author.email | lower }}>
 * @copyright  Copyright (C){{ "now" | date('Y') }} {{ copyright }}. All rights reserved.
 * @license    {{ project.license }}
{# endalign #}
 */

/** The file with the code under test */
require_once SOURCE_DIR . '/administrator/models/{{ entity.name | singular | file }}.php';

/**
 * Tests for {{ entity.name | class }} Item Model
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}.UnitTest
 * @since      1.0.0
{# endalign #}
 */
class {{ project.name | class }}AdminModel{{ entity.name | class }}Test extends PHPUnit_Framework_TestCase
{
	/**
	 * The object under test
	 *
	 * @var  {{ project.name | class }}AdminModel{{ entity.name | class }}
	 */
	protected $object;

	/**
	 * Set up the fixture
	 *
	 * @return  void
	 */
	protected function setUp()
	{
		$this->object = new {{ project.name | class }}AdminModel{{ entity.name | class }};
	}

	/**
	 * Tear down the fixture
	 *
	 * @return  void
	 */
	protected function tearDown()
	{
	}

	/**
	 * @return  void
	 *
	 * @todo    Implement testConstruct().
	 */
	public function testConstruct()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
{% if entity.special.category %}

	/**
	 * @return  void
	 *
	 * @todo    Implement testBatchCopy().
	 */
	public function testBatchCopy()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * @return  void
	 *
	 * @todo    Implement testCopyEntry().
	 */
	public function testCopyEntry()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * @return  void
	 *
	 * @todo    Implement testCanDelete().
	 */
	public function testCanDelete()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * @return  void
	 *
	 * @todo    Implement testCanEditState().
	 */
	public function testCanEditState()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
{% endif %}

	/**
	 * @return  void
	 *
	 * @todo    Implement testGetTable().
	 */
	public function testGetTable()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
{% if entity.special.attribs %}

	/**
	 * @return  void
	 *
	 * @todo    Implement testGetItem().
	 */
	public function testGetItem()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
{% endif %}

	/**
	 * @return  void
	 *
	 * @todo    Implement testGetForm().
	 */
	public function testGetForm()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * @return  void
	 *
	 * @todo    Implement testLoadFormData().
	 */
	public function testLoadFormData()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
{% if entity.special.attribs or entity.special.featured %}

	/**
	 * @return  void
	 *
	 * @todo    Implement testSave().
	 */
	public function testSave()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
{% endif %}
{% if entity.special.featured %}

	/**
	 * @return  void
	 *
	 * @todo    Implement testFeatured().
	 */
	public function testFeatured()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
{% endif %}
{% if entity.special.sticky %}

	/**
	 * @return  void
	 *
	 * @todo    Implement testStick().
	 */
	public function testStick()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
{% endif %}
{% if entity.special.featured or entity.special.sticky %}

	/**
	 * @return  void
	 *
	 * @todo    Implement testSetFlag().
	 */
	public function testSetFlag()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
{% endif %}
{% if entity.special.ordering %}

	/**
	 * @return  void
	 *
	 * @todo    Implement testGetReorderConditions().
	 */
	public function testGetReorderConditions()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
{% endif %}

	/**
	 * @return  void
	 *
	 * @todo    Implement testPrepareTable().
	 */
	public function testPrepareTable()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
}
