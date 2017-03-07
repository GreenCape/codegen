<?template scope="entity"?>
<?php
/**
 * {{ project.title }} {{ entity.name | title }} Table Tests
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
require_once SOURCE_DIR . '/administrator/tables/{{ entity.name | plural | file }}.php';

/**
 * Tests for {{ entity.name | class }} Table
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}.UnitTest
 * @since      1.0.0
{# endalign #}
 */
class {{ project.name | class }}AdminTable{{ entity.name | plural | class }}Test extends PHPUnit_Framework_TestCase
{
	/**
	 * The object under test
	 *
	 * @var {{ project.name | class }}Table{{ entity.name | plural | class }}
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
		JFactory::$application    = JApplication::getMock($this);
		JFactory::$database       = JDatabase::getMock($this);
		JFactory::$date           = JDate::getMock($this);

		$this->object = new {{ project.name | class }}Table{{ entity.name | plural | class }}(JFactory::getDbo());
	}

	/**
	 * Tear down the fixture
	 *
	 * @return  void
	 */
	protected function tearDown()
	{
		JFactory::$database       = null;
		JFactory::$application    = null;
		JFactory::$staticTestCase = null;
	}

	/**
	 * Test if __construct() returns a Table
	 *
	 */
	public function testConstructorReturnsATable()
	{
		$this->assertTrue($this->object instanceof JTable);
	}
{% if entity.special.attribs %}

	/**
	 * @return  void
	 *
	 * @todo    Implement testBind().
	 */
	public function testBind()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
{% endif %}

	/**
	 * @return  void
	 *
	 * @todo    Implement testCheck().
	 */
	public function testCheck()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
{% if (entity.special.modified and entity.special.modified_by and entity.special.created and entity.special.created_by) or entity.special.alias %}

	/**
	 * @return  void
	 *
	 * @todo    Implement testStore().
	 */
	public function testStore()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
{% endif %}
{% if entity.special.published %}

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
{% if entity.special.published or entity.special.featured or entity.special.sticky %}

	/**
	 * @return  void
	 *
	 * @todo    Implement testSetFlag().
	 */
	public function testSetFlag()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
{% endif %}

	/**
	 * @return  void
	 *
	 * @todo    Implement testGetBaseQuery().
	 */
	public function testGetBaseQuery()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Provide test data for applyFilterAccess()
	 *
	 * @return  array
	 */
	public function dataApplyFilterAccess()
	{
		return array(
			'enabled-no_admin' => array(
				array(
					'filter.expect'     => $this->atLeastOnce(),
					'filter.return'     => 1,
					'authorise.expect'  => $this->any(),
					'authorise.return'  => 0,
					'viewlevels.expect' => $this->atLeastOnce(),
					'viewlevels.return' => array(1, 1, 2),
					'result.expect'     => $this->once(),
					'result.value'      => '`compiled_access` IN (1, 2)',
				),
			),
			'enabled-admin' => array(
				array(
					'filter.expect'     => $this->any(),
					'filter.return'     => 1,
					'authorise.expect'  => $this->any(),
					'authorise.return'  => 1,
					'viewlevels.expect' => $this->any(),
					'viewlevels.return' => array(1, 1, 2),
					'result.expect'     => $this->never(),
					'result.value'      => null,
				),
			),
			'disabled-no_admin' => array(
				array(
					'filter.expect'     => $this->atLeastOnce(),
					'filter.return'     => 0,
					'authorise.expect'  => $this->any(),
					'authorise.return'  => 0,
					'viewlevels.expect' => $this->any(),
					'viewlevels.return' => array(1, 1, 2),
					'result.expect'     => $this->never(),
					'result.value'      => null,
				),
			),
			'disabled-admin' => array(
				array(
					'filter.expect'     => $this->any(),
					'filter.return'     => 0,
					'authorise.expect'  => $this->any(),
					'authorise.return'  => 1,
					'viewlevels.expect' => $this->any(),
					'viewlevels.return' => array(1, 1, 2),
					'result.expect'     => $this->never(),
					'result.value'      => null,
				),
			),
			'enabled-0/1/2' => array(
				array(
					'filter.expect'     => $this->atLeastOnce(),
					'filter.return'     => 1,
					'authorise.expect'  => $this->any(),
					'authorise.return'  => 0,
					'viewlevels.expect' => $this->atLeastOnce(),
					'viewlevels.return' => array(0, 1, 2),
					'result.expect'     => $this->once(),
					'result.value'      => '`compiled_access` IN (0, 1, 2)',
				),
			),
			'enabled-1' => array(
				array(
					'filter.expect'     => $this->atLeastOnce(),
					'filter.return'     => 1,
					'authorise.expect'  => $this->any(),
					'authorise.return'  => 0,
					'viewlevels.expect' => $this->atLeastOnce(),
					'viewlevels.return' => array(1),
					'result.expect'     => $this->once(),
					'result.value'      => '`compiled_access` IN (1)',
				),
			),
		);
	}

	/**
	 * Test if access filter is applied correctly
	 *
	 * @todo          Investigate, if the applyFilter* methods belong to their own class
	 * @dataProvider  dataApplyFilterAccess
	 */
	public function testApplyFilterAccess($data)
	{
		// Prerequisites

		$db = JFactory::getDbo();

		$state = $this->getMock('JObject', array('get'), array(), '', false);
		$state->expects($data['filter.expect'])
			->method('get')
			->with('filter.access')
			->will($this->returnValue($data['filter.return']));

		$user = $this->getMock('JUser', array('authorise', 'getAuthorisedViewLevels'), array(), '', false);
		$user->expects($data['authorise.expect'])
			->method('authorise')
			->with('core.admin')
			->will($this->returnValue($data['authorise.return']));
		$user->expects($data['viewlevels.expect'])
			->method('getAuthorisedViewLevels')
			->will($this->returnValue($data['viewlevels.return']));

		$session = $this->getMock('JSession', array('get'), array(), '', false);
		$session->expects($this->any())
			->method('get')
			->with('user')
			->will($this->returnValue($user));

		JFactory::$session = $session;

		// Expected result

		$query = $this->getMock('JDatabaseQuery', array('having', 'where'), array(), '', false);
		if (is_null($data['result.value']))
		{
			$query->expects($data['result.expect'])
				->method('having');
		}
		else
		{
			$query->expects($data['result.expect'])
				->method('having')
				->with($data['result.value']);
		}

		// Execute

		$method = new ReflectionMethod('{{ project.name | class }}Table{{ entity.name | plural | class }}', 'applyFilterAccess');
		$method->setAccessible(true);
		$method->invoke($this->object, $db, $query, $state, $user);
	}
{% if entity.special.created_by %}

	/**
	 * Provide test data for applyViewRestriction()
	 *
	 * @return  array
	 */
	public function dataApplyViewRestriction()
	{
		return array(
			'1-all-0' => array(
				array(
					'user.id'                 => 1,
					'params.view_restriction' => 'all',
					'params.show_noauth'      => 0,
					'result.expect'           => $this->never(),
					'result.value'            => null,
				),
			),
			'1-all-1' => array(
				array(
					'user.id'                 => 1,
					'params.view_restriction' => 'all',
					'params.show_noauth'      => 1,
					'result.expect'           => $this->never(),
					'result.value'            => null,
				),
			),
			'1-own-0' => array(
				array(
					'user.id'                 => 1,
					'params.view_restriction' => 'own',
					'params.show_noauth'      => 0,
					'result.expect'           => $this->once(),
					'result.value'            => '`{{ entity.name }}.{{ entity.special.created_by.name }}` = 1',
				),
			),
			'1-own-1' => array(
				array(
					'user.id'                 => 1,
					'params.view_restriction' => 'own',
					'params.show_noauth'      => 1,
					'result.expect'           => $this->never(),
					'result.value'            => null,
				),
			),
			'2-all-0' => array(
				array(
					'user.id'                 => 2,
					'params.view_restriction' => 'all',
					'params.show_noauth'      => 0,
					'result.expect'           => $this->never(),
					'result.value'            => null,
				),
			),
			'2-all-1' => array(
				array(
					'user.id'                 => 2,
					'params.view_restriction' => 'all',
					'params.show_noauth'      => 1,
					'result.expect'           => $this->never(),
					'result.value'            => null,
				),
			),
			'2-own-0' => array(
				array(
					'user.id'                 => 2,
					'params.view_restriction' => 'own',
					'params.show_noauth'      => 0,
					'result.expect'           => $this->once(),
					'result.value'            => '`{{ entity.name }}.{{ entity.special.created_by.name }}` = 2',
				),
			),
			'2-own-1' => array(
				array(
					'user.id'                 => 2,
					'params.view_restriction' => 'own',
					'params.show_noauth'      => 1,
					'result.expect'           => $this->never(),
					'result.value'            => null,
				),
			),
		);
	}

	/**
	 * @param	array	$data	  The test data set
	 *
	 * @return  void
	 *
	 * @dataProvider  dataApplyViewRestriction
	 */
	public function testApplyFilterViewRestriction($data)
	{
		// Prerequisites

		$db = JFactory::getDbo();

		$params = $this->getMock('JRegistry', array('get'), array(), '', false);
		$params->expects($this->any())
			->method('get')
			->will(
				$this->returnValueMap(
					array(
						array('{{ entity.name | singular | file }}_view_restriction', 'all', $data['params.view_restriction']),
						array('{{ entity.name | singular | file }}_show_noauth', 0, $data['params.show_noauth']),
					)
				)
			);

		$user = $this->getMock('JUser', array('get'), array(), '', false);
		$user->expects($this->any())
			->method('get')
			->with('id')
			->will($this->returnValue($data['user.id']));

		// Expected result

		$query = $this->getMock('JDatabaseQuery', array('having', 'where'), array(), '', false);
		if (is_null($data['result.value']))
		{
			$query->expects($data['result.expect'])
				->method('where');
		}
		else
		{
			$query->expects($data['result.expect'])
				->method('where')
				->with($data['result.value']);
		}

		// Execute

		$method = new ReflectionMethod('{{ project.name | class }}Table{{ entity.name | plural | class }}', 'applyFilterViewRestriction');
		$method->setAccessible(true);
		$method->invoke($this->object, $db, $query, $user, $params);
	}
{% endif %}

	/**
	 * @return  void
	 *
	 * @todo    Implement testApplyFilterState().
	 */
	public function testApplyFilterState()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
{% if entity.special.featured %}

	/**
	 * @return  void
	 *
	 * @todo    Implement testApplyFilterFeatured().
	 */
	public function testApplyFilterFeatured()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
{% endif %}

	/**
	 * @return  void
	 *
	 * @todo    Implement testApplyFilterId().
	 */
	public function testApplyFilterId()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
{% if entity.special.category %}

	/**
	 * @return  void
	 *
	 * @todo    Implement testApplyFilterCategory().
	 */
	public function testApplyFilterCategory()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
{% endif %}
{% if entity.special.created_by or entity.special.author_alias %}

	/**
	 * @return  void
	 *
	 * @todo    Implement testApplyFilterAuthor().
	 */
	public function testApplyFilterAuthor()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
{% endif %}
{% if entity.special.publish_up %}


	/**
	 * @return  void
	 *
	 * @todo    Implement testApplyFilterPublishingPeriod().
	 */
	public function testApplyFilterPublishingPeriod()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
{% endif %}
{% if entity.special. created or entity.special. modified or entity.special. publish_up %}

	/**
	 * @return  void
	 *
	 * @todo    Implement testApplyFilterDateRange().
	 */
	public function testApplyFilterDateRange()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
{% endif %}

	/**
	 * @return  void
	 *
	 * @todo    Implement testApplyFilterListFilter().
	 */
	public function testApplyFilterListFilter()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
{% if entity.special.language %}


	/**
	 * @return  void
	 *
	 * @todo    Implement testApplyFilterLanguage().
	 */
	public function testApplyFilterLanguage()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
{% endif %}
{% for property in entity.references.foreignKeys  %}
{% set foreignEntity = project.entities[property.type.entity] %}
{% if property.role == 'category' %}
{% elseif property.role == 'created_by' %}
{% else %}

	/**
	 * @return  void
	 *
	 * @todo    Implement testApplyFilter{{ property.name | class }}().
	 */
	public function testApplyFilter{{ property.name | class }}()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
{% endif %}
{% endfor %}

	/**
	 * @return  void
	 *
	 * @todo    Implement testApplyFilterSearch().
	 */
	public function testApplyFilterSearch()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * @return  void
	 *
	 * @todo    Implement testApplyOrdering().
	 */
	public function testApplyOrdering()
	{
		// Remove the following line when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
}
