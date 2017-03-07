<?template scope="application"?>
<?php
/**
 * {{ project.title }} Admin Controller Tests
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
require_once SOURCE_DIR . '/administrator/controller.php';

/**
 * Tests for {{ project.title }} Admin Controller
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}.UnitTest
 * @since      1.0.0
{# endalign #}
 */
class {{ project.name | class }}AdminControllerTest extends PHPUnit_Framework_TestCase
{
	/**
	 * The object under test
	 *
	 * @var {{ project.name | class }}AdminController
	 */
	protected $object;

	/**
	 * Set up the fixture
	 *
	 * @return  void
	 */
	protected function setUp()
	{
		JFactory::$staticTestCase = $this;

		$this->object = new {{ project.name | class }}AdminController(
			array(
				 'base_path' => realpath(SOURCE_DIR . '/administrator'),
			)
		);
	}

	/**
	 * Tear down the fixture
	 *
	 * @return  void
	 */
	protected function tearDown()
	{
		JFactory::$staticTestCase = null;
	}

	/**
	 * Test if __construct() returns a Controller
	 *
	 */
	public function testConstructorReturnsAController()
	{
		$this->assertTrue($this->object instanceof JController);
	}

	/**
	 * Provide test data for display()
	 *
	 * @return  array
	 */
	public function dataDisplay()
	{
		return array(
{% for entity in project.entities %}

			'{{ entity.name | singular | file }}-default-1' => array(
				'inputMap' => array(
					array('view', 'events', '{{ entity.name | singular | file }}'),
					array('layout', 'default', 'default'),
					array('id', null, 1),
				),
				'expected' => 'succeed',
			),
			'{{ entity.name | singular | file }}-default-2' => array(
				'inputMap' => array(
					array('view', 'events', '{{ entity.name | singular | file }}'),
					array('layout', 'default', 'default'),
					array('id', null, 2),
				),
				'expected' => 'succeed',
			),
			'{{ entity.name | singular | file }}-edit-1' => array(
				'inputMap' => array(
					array('view', 'events', '{{ entity.name | singular | file }}'),
					array('layout', 'default', 'edit'),
					array('id', null, 1),
				),
				'expected' => 'fail',
			),
			'{{ entity.name | singular | file }}-edit-2' => array(
				'inputMap' => array(
					array('view', 'events', '{{ entity.name | singular | file }}'),
					array('layout', 'default', 'edit'),
					array('id', null, 2),
				),
				'expected' => 'succeed',
			),
{% endfor %}
		);
	}

	/**
	 * @param	array	$inputMap	  The input variables
	 * @param	string	$expected	  The expected result
	 *
	 * @return  void
	 *
	 * @dataProvider  dataDisplay
	 */
	public function testDisplay($inputMap, $expected)
	{
		$this->object->testCase = $this;

		$input = $this->getMock('JInput', array('getCmd', 'getInt'), array(), '', false);
		$input->expects($this->any())
			->method('getCmd')
			->will($this->returnValueMap($inputMap));
		$input->expects($this->any())
			->method('getInt')
			->will($this->returnValueMap($inputMap));

		JFactory::$application = JApplication::getMock($this);
		JFactory::$application->input = $input;

		if ($expected == 'fail')
		{
			$this->assertFalse($this->object->display());
		}
		else
		{
			$this->assertInstanceOf('{{ project.name | class }}AdminController', $this->object->display());
		}
	}

	/**
	 * Mock method for JController::checkEditId() - will accept id == 2
	 *
	 * @param	string	$context	  The context; ignored
	 * @param	int	$id	  The id
	 *
	 * @return  bool
	 */
	public function mockJControllerCheckEditId($context, $id)
	{
		return $id == 2;
	}
}
