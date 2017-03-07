<?template scope="entity"?>
<?php
/**
 * {{ project.title }} {{ entity.name | singular | title }} Admin List Controller Tests
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
require_once SOURCE_DIR . '/administrator/controllers/{{ entity.name | plural | file }}.php';

/**
 * Test for {{ entity.name | singular | title }} list controller class.
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}.UnitTest
 * @since      1.0.0
{# endalign #}
 */
class {{ project.name | class }}AdminController{{ entity.name | plural | class }}Test extends PHPUnit_Framework_TestCase
{
	/**
	 * The object under test
	 *
	 * @var  {{ project.name | class }}AdminController{{ entity.name | plural | class }}
	 */
	protected $object;

	/**
	 * Set up the fixture
	 *
	 * @return  void
	 */
	protected function setUp()
	{
		JControllerAdmin::$staticTestCase = $this;
		$this->object = new {{ project.name | class }}AdminController{{ entity.name | plural | class }};
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

	/**
	 * @return  void
	 *
	 * @todo    Implement testGetModel().
	 */
	public function testGetModel()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
{% if entity.role == 'lookup' %}

	/**
	 * @return  void
	 *
	 * @todo    Implement testDelete().
	 */
	public function testDelete()
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

	/**
	 * @return  void
	 *
	 * @todo    Implement testGetRedirectToListAppend().
	 */
	public function testGetRedirectToListAppend()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * @return  void
	 *
	 * @todo    Implement testGetRedirectToItemAppend().
	 */
	public function testGetRedirectToItemAppend()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
}
