<?template scope="entity"?>
{% if entity.references.users %}
<?php
/**
 * {{ project.title }} User Modal Form Field Tests
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
require_once SOURCE_DIR . '/administrator/models/fields/modal/user.php';

/**
 * Tests for User Modal Field Class for the Joomla Framework.
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}.UnitTest
 * @since      1.0.0
{# endalign #}
 */
class JFormFieldModal_UserTest extends PHPUnit_Framework_TestCase
{
	/**
	 * The object under test
	 *
	 * @var  JFormFieldModal_UserTest
	 */
	protected $object;

	/**
	 * Set up the fixture
	 *
	 * @return  void
	 */
	protected function setUp()
	{
		$this->object = new JFormFieldModal_UserTest;
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
	 * @todo    Implement testetInput().
	 */
	public function testetInput()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
}
{% endif %}
