<?template scope="application"?>
<?php
/**
 * {{ project.title }} Frontend Route Helper Tests
 *
 * PHP version 5.3
 *
{# align("\t", '  ') #}
 * @version    {{ project.version }}
 * @package    {{ project.name | class }}.UnitTest
{% for author in project.authors %}
 * @author     {{ author.name }} <{{ author.email | lower }}>
{% endfor %}
 * @copyright  Copyright (C){{ "now" | date('Y') }} {{ project.copyright }}. All rights reserved.
 * @license    {{ project.license }}
{# endalign #}
 */

/** The file with the code under test */
require_once SOURCE_DIR . '/site/helpers/route.php';

/**
 * Tests for {{ project.title }} Route Helper
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}.UnitTest
 * @since      1.0.0
{# endalign #}
 */
class {{ project.name | class }}HelperRouteTest extends PHPUnit_Framework_TestCase
{
	/**
	 * The object under test
	 *
	 * @var  {{ project.name | class }}HelperRoute
	 */
	protected $object;

	/**
	 * Set up the fixture
	 *
	 * @return  void
	 */
	protected function setUp()
	{
		$this->object = new {{ project.name | class }}HelperRoute;
	}

	/**
	 * Tear down the fixture
	 *
	 * @return  void
	 */
	protected function tearDown()
	{
	}
{% for entity in project.entities %}
{% if entity.role != 'lookup' and entity.role != 'map' %}

	/**
	 * @return  void
	 *
	 * @todo    Implement testGet{{ entity.name | class }}Route().
	 */
	public function testGet{{ entity.name | class }}Route()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * @return  void
	 *
	 * @todo    Implement testGet{{ entity.name | class }}FormRoute().
	 */
	public function testGet{{ entity.name | class }}FormRoute()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
{% if entity.special.category %}

	/**
	 * @return  void
	 *
	 * @todo    Implement testGet{{ entity.name | class }}CategoryRoute().
	 */
	public function testGet{{ entity.name | class }}CategoryRoute()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
{% endif %}
{% endif %}
{% endfor %}

	/**
	 * @return  void
	 *
	 * @todo    Implement test_findItem().
	 */
	public function test_findItem()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * @return  void
	 *
	 * @todo    Implement testInitializeLookup().
	 */
	public function testInitializeLookup()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * @return  void
	 *
	 * @todo    Implement testGetItemId().
	 */
	public function testGetItemId()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
}
