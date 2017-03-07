<?template scope="application"?>
<?php
/**
 * {{ project.title }} Frontend Router Tests
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
require_once SOURCE_DIR . '/site/router.php';

/**
 * Tests for {{ project.title }} Frontend Router
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}.UnitTest
 * @since      1.0.0
{# endalign #}
 */
class {{ project.name | class }}RouterTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Set up the fixture
	 *
	 * @return  void
	 */
	protected function setUp()
	{
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
	 * @todo    Implement test{{ project.name | class }}BuildRoute().
	 */
	public function test{{ project.name | class }}BuildRoute()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * @return  void
	 *
	 * @todo    Implement test{{ project.name | class }}ParseRoute().
	 */
	public function test{{ project.name | class }}ParseRoute()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
}
