<?template scope="entity"?>
<?php
/**
 * {{ project.title }} {{ entity.name | title }} Admin HTML List View Tests
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
require_once SOURCE_DIR . '/administrator/views/{{ entity.name | plural | file }}/view.html.php';

/**
 * Tests for {{ entity.name | class }} Admin HTML List View
 *
{# align("\t", '  ') #}

 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}.UnitTest
 * @since      1.0.0
{# endalign #}

 */
class {{ project.name | class }}AdminView{{ entity.name | plural | class }}Test extends PHPUnit_Framework_TestCase
{
	/**
	 * The object under test
	 *
	 * @var  {{ project.name | class }}AdminView{{ entity.name | plural | class }}
	 */
	protected $object;

	/**
	 * Set up the fixture
	 *
	 * @return  void
	 */
	protected function setUp()
	{
		$this->object = new {{ project.name | class }}AdminView{{ entity.name | plural | class }};
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
	 * @todo    Implement testDisplay().
	 */
	public function testDisplay()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * @return  void
	 *
	 * @todo    Implement testAddToolbar().
	 */
	public function testAddToolbar()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * @return  void
	 *
	 * @todo    Implement testToggle().
	 */
	public function testToggle()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
}
