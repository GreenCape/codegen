<?template scope="entity"?>
{% if entity.role != 'lookup' and entity.role != 'map' and entity.special.published %}
<?php
/**
 * {{ project.title }} {{ entity.name | title }} Archive Controller Tests
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
require_once SOURCE_DIR . '/site/models/{{ entity.name | singular | file }}_archive.php';

/**
 * Tests for {{ entity.name | class }} Archive Model
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}.UnitTest
 * @since      1.0.0
{# endalign #}
 */
class {{ project.name | class }}Model{{ entity.name | class }}_ArchiveTest extends PHPUnit_Framework_TestCase
{
    /**
     * The object under test
     *
     * @var  {{ project.name | class }}Model{{ entity.name | class }}_Archive
     */
    protected $object;

    /**
     * Set up the fixture
     *
     * @return  void
     */
    protected function setUp()
    {
        $this->object = new {{ project.name | class }}Model{{ entity.name | class }}_Archive;
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
     * @todo    Implement testPopulateState().
     */
    public function testPopulateState()
	{
        // Remove the following line when you implement this test.
        $this->markTestIncomplete('This test has not been implemented yet.');
	}

    /**
     * @return  void
     *
     * @todo    Implement testGetListQuery().
     */
    public function testGetListQuery()
	{
        // Remove the following line when you implement this test.
        $this->markTestIncomplete('This test has not been implemented yet.');
	}

    /**
     * @return  void
     *
     * @todo    Implement testGetData().
     */
    public function testGetData()
	{
        // Remove the following line when you implement this test.
        $this->markTestIncomplete('This test has not been implemented yet.');
	}

    /**
     * @return  void
     *
     * @todo    Implement test_getList().
     */
    public function test_getList()
	{
        // Remove the following line when you implement this test.
        $this->markTestIncomplete('This test has not been implemented yet.');
	}
}
{% endif %}
