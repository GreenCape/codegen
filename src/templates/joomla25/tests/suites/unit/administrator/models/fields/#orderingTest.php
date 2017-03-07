<?template scope="entity"?>
{% if entity.special.ordering %}
<?php
/**
 * {{ project.title }} {{ entity.name | title }} Ordering Form Field Tests
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
require_once SOURCE_DIR . '/administrator/models/fields/{{ entity.name | singular | file }}ordering.php';

/**
 * Tests for {{ entity.name | class }} Ordering Form Field
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}.UnitTest
 * @since      1.0.0
{# endalign #}
 */
class JFormField{{ entity.name | class }}OrderingTest extends PHPUnit_Framework_TestCase
{
	/**
	 * The object under test
	 *
	 * @var  JFormField{{ entity.name | class }}Ordering
	 */
	protected $object;

	/**
	 * Set up the fixture
	 *
	 * @return  void
	 */
	protected function setUp()
	{
		$this->object = new JFormField{{ entity.name | class }}Ordering;
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
}
{% endif %}
