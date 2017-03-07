<?template scope="application"?>
<?php
/**
 * {{ project.title }} Frontend Query Helper Tests
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
require_once SOURCE_DIR . '/site/helpers/query.php';

/**
 * Tests for {{ project.title }} Query Helper
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}.UnitTest
 * @since      1.0.0
{# endalign #}
 */
class {{ project.name | class }}HelperQueryTest extends PHPUnit_Framework_TestCase
{
	/**
	 * The object under test
	 *
	 * @var  {{ project.name | class }}HelperQuery
	 */
	protected $object;

	/**
	 * Set up the fixture
	 *
	 * @return  void
	 */
	protected function setUp()
	{
		$this->object = new {{ project.name | class }}HelperQuery;
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
	 * @todo    Implement testOrder{{ entity.name | plural | class }}BySecondary().
	 */
	public function testOrder{{ entity.name | plural | class }}BySecondary()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * @return  void
	 *
	 * @todo    Implement testGet{{ entity.name | class }}QueryDate().
	 */
	public function testGet{{ entity.name | class }}QueryDate()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
{% endif %}
{% endfor %}
}
