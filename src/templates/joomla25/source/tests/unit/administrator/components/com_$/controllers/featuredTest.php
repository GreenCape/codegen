<?template scope="entity"?>
{% if entity.role == 'main' and entity.special.featured %}
<?php
/**
 * {{ project.title }} Featured {{ entity.name | singular | title }} Admin List Controller Tests
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
require_once SOURCE_DIR . '/administrator/controllers/featured.php';

/**
 * Tests for Featured {{ entity.name | singular | title }} list controller class.
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}.UnitTest
 * @since      1.0.0
{# endalign #}
 */
class {{ project.name | class }}AdminControllerFeaturedTest extends PHPUnit_Framework_TestCase
{
	/**
	 * The object under test
	 *
	 * @var  {{ project.name | class }}AdminControllerFeatured
	 */
	protected $object;

	/**
	 * Set up the fixture
	 *
	 * @return  void
	 */
	protected function setUp()
	{
		$this->object = new {{ project.name | class }}AdminControllerFeatured;
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
	 * @todo    Implement testDelete().
	 */
	public function testDelete()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * @return  void
	 *
	 * @todo    Implement testPublish().
	 */
	public function testPublish()
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
}
{% endif %}
