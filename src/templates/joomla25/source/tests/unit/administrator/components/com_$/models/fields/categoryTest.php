<?template scope="entity"?>
{% if entity.special.category %}
<?php
/**
 * {{ project.title }} Categories Form Field Tests
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
require_once SOURCE_DIR . '/administrator/models/fields/category.php';

/**
 * Tests for Categories Field Class for the Joomla Framework.
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}.UnitTest
 * @since      1.0.0
{# endalign #}
 */
class JFormFieldCategoryTest extends PHPUnit_Framework_TestCase
{
	/**
	 * The object under test
	 *
	 * @var  JFormFieldCategory
	 */
	protected $object;

	/**
	 * Set up the fixture
	 *
	 * @return  void
	 */
	protected function setUp()
	{
		$this->object = new JFormFieldCategory;
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
	 * @todo    Implement testGetInput().
	 */
	public function testGetInput()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * @return  void
	 *
	 * @todo    Implement testGetOptions().
	 */
	public function testGetOptions()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
}
{% endif %}
