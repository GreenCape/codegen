<?template scope="application"?>
<?php
/**
 * {{ project.title }} Installation Script Tests
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
require_once SOURCE_DIR . '/install.php';

/**
 * Tests for {{ project.title }} Installer
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}.UnitTest
 * @since      1.0.0
{# endalign #}
 */
class com_{{ project.name | file }}InstallerScriptTest extends PHPUnit_Framework_TestCase
{
	/**
	 * The object under test
	 *
	 * @var  com_{{ project.name | file }}InstallerScript
	 */
	protected $object;

	/**
	 * Fake installer component
	 *
	 * @var  JInstallerComponent
	 */
	protected $installer;

	/**
	 * Joomla version for JVersion mock
	 *
	 * @var  string
	 */
	private $version;

	/**
	 * Set up the fixture
	 *
	 * @return  void
	 */
	protected function setUp()
	{
		JFactory::$staticTestCase = $this;
		JFactory::$application = JApplication::getMock($this);
		JVersion::$staticTestCase = $this;

		$this->object    = new com_aelterwerdenInstallerScript;
		$this->installer = $this->getMock('JInstallerComponent');
	}

	/**
	 * Tear down the fixture
	 *
	 * @return  void
	 */
	protected function tearDown()
	{
		JVersion::$staticTestCase = null;
		JFactory::$staticTestCase = null;
	}

	/**
	 * Test if install() exists
	 *
	 * This test is needed, because there is no interface defined to ensure
	 * the existence of the method.
	 *
	 * @return  void
	 *
	 */
	public function testInstallExists()
	{
		$this->assertTrue(is_callable(array($this->object, 'install')));
	}

	/**
	 * Test if install() returns true
	 *
	 * @return  void
	 *
	 */
	public function testInstallReturnsSuccess()
	{
		$this->assertTrue($this->object->install($this->installer));
	}

	/**
	 * Test if uninstall() exists
	 *
	 * This test is needed, because there is no interface defined to ensure
	 * the existence of the method.
	 *
	 * @return  void
	 *
	 */
	public function testUninstallExists()
	{
		$this->assertNull($this->object->uninstall($this->installer));
	}

	/**
	 * Test if update() exists
	 *
	 * This test is needed, because there is no interface defined to ensure
	 * the existence of the method.
	 *
	 * @return  void
	 *
	 */
	public function testUpdateExists()
	{
		$this->assertTrue(is_callable(array($this->object, 'update')));
	}

	/**
	 * Test if update() returns true
	 *
	 * @return  void
	 *
	 */
	public function testUpdateReturnsSuccess()
	{
		$this->assertTrue($this->object->update($this->installer));
	}

	public function dataPreflight()
	{
		return array(
			array('discover_install'),
			array('install'),
			array('update'),
		);
	}

	/**
	 * Test if preflight() exists
	 *
	 * This test is needed, because there is no interface defined to ensure
	 * the existence of the method.
	 *
	 * @return  void
	 *
	 */
	public function testPreflightExists()
	{
		$this->assertTrue(is_callable(array($this->object, 'preflight')));
	}

	/**
	 * Test if preflight() rejects old Joomla versions
	 *
	 * @param	string	$type	  The installer mode
	 *
	 * @return  void
	 *
	 * @dataProvider  dataPreflight
	 */
	public function testPreflightFailsOnOldJoomlaVersion($type)
	{
		$this->version = '2.4.9';
		$this->assertFalse($this->object->preflight($type, $this->installer));
	}

	/**
	 * Test if preflight() accepts Joomla versions above 2.5 (incl)
	 *
	 * @param	string	$type	  The installer mode
	 *
	 * @return  void
	 *
	 * @dataProvider  dataPreflight
	 */
	public function testPreflightSucceedsOnJoomlaVersion25($type)
	{
		$this->version = '2.5.0';
		$this->assertTrue($this->object->preflight($type, $this->installer));
	}

	/**
	 * Mock method for JVersion::getShortVersion() - return prepared version number
	 *
	 * @return  string
	 */
	public function mockJVersionGetShortVersion()
	{
		return $this->version;
	}

	/**
	 * Provide test data for postflight() install mode
	 *
	 * @return  array
	 */
	public function dataPostflightInstall()
	{
		return array(
			array('discover_install'),
			array('install'),
		);
	}

	/**
	 * Provide test data for postflight() update mode
	 *
	 * @return  array
	 */
	public function dataPostflightUpdate()
	{
		return array(
			array('update'),
		);
	}

	/**
	 * Test if postflight() exists
	 *
	 * This test is needed, because there is no interface defined to ensure
	 * the existence of the method.
	 *
	 * @return  void
	 *
	 */
	public function testPostflightExists()
	{
		$this->assertTrue(is_callable(array($this->object, 'postflight')));
	}

	/**
	 * Test if postflight() sets asset rules on install
	 *
	 * @param	string	$type	  The installer mode
	 *
	 * @return  void
	 *
	 * @dataProvider  dataPostflightInstall
	 */
	public function testPostflightSetsAssetRulesOnInstall($type)
	{
		JFactory::$database = JDatabase::getMock($this);
		JFactory::$database
			->expects($this->once())
			->method('setQuery')
			->with($this->stringStartsWith('UPDATE #__assets SET rules'))
			->will($this->returnSelf());
		JFactory::$database
			->expects($this->once())
			->method('execute');

		$this->object->postflight($type, $this->installer);
	}

	/**
	 * Test if postflight() does not alter asset rules on update
	 *
	 * @param	string	$type	  The installer mode
	 *
	 * @return  void
	 *
	 * @dataProvider  dataPostflightUpdate
	 */
	public function testPostflightDoesNotAlterAssetRulesOnUpdate($type)
	{
		JFactory::$database = JDatabase::getMock($this);
		JFactory::$database
			->expects($this->never())
			->method('setQuery');

		$this->object->postflight($type, $this->installer);
	}
}
