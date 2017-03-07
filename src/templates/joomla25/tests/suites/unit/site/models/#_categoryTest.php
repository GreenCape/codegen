<?template scope="entity"?>
{% if entity.role != 'lookup' and entity.role != 'map' and entity.special.category %}
<?php
/**
 * {{ project.title }} {{ entity.name | title }} Category Controller Tests
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
require_once SOURCE_DIR . '/site/models/{{ entity.name | singular | file }}_category.php';

/**
 * Tests for {{ entity.name | singular | title }} Category Model
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}.UnitTest
 * @since      1.0.0
{# endalign #}
 */
class {{ project.name | class }}Model{{ entity.name | class }}_CategoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * The object under test
     *
     * @var  {{ project.name | class }}Model{{ entity.name | class }}_Category
     */
    protected $object;

    /**
     * Set up the fixture
     *
     * @return  void
     */
    protected function setUp()
    {
        $this->object = new {{ project.name | class }}Model{{ entity.name | class }}_Category;
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
     * @todo    Implement testConstruct().
     */
    public function testConstruct()
	{
        // Remove the following line when you implement this test.
        $this->markTestIncomplete('This test has not been implemented yet.');
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
     * @todo    Implement testGetItems().
     */
    public function testGetItems()
	{
        // Remove the following line when you implement this test.
        $this->markTestIncomplete('This test has not been implemented yet.');
	}

    /**
     * @return  void
     *
     * @todo    Implement test_buildContentOrderBy().
     */
    public function test_buildContentOrderBy()
	{
        // Remove the following line when you implement this test.
        $this->markTestIncomplete('This test has not been implemented yet.');
	}

    /**
     * @return  void
     *
     * @todo    Implement testGetPagination().
     */
    public function testGetPagination()
	{
        // Remove the following line when you implement this test.
        $this->markTestIncomplete('This test has not been implemented yet.');
	}

    /**
     * @return  void
     *
     * @todo    Implement testGetCategory().
     */
    public function testGetCategory()
	{
        // Remove the following line when you implement this test.
        $this->markTestIncomplete('This test has not been implemented yet.');
	}

    /**
     * @return  void
     *
     * @todo    Implement testGetParent().
     */
    public function testGetParent()
	{
        // Remove the following line when you implement this test.
        $this->markTestIncomplete('This test has not been implemented yet.');
	}

    /**
     * @return  void
     *
     * @todo    Implement testGetLeftSibling().
     */
    public function testGetLeftSibling()
	{
        // Remove the following line when you implement this test.
        $this->markTestIncomplete('This test has not been implemented yet.');
	}

    /**
     * @return  void
     *
     * @todo    Implement testGetRightSibling().
     */
    public function testGetRightSibling()
	{
        // Remove the following line when you implement this test.
        $this->markTestIncomplete('This test has not been implemented yet.');
	}

    /**
	 * @return  void
	 *
     * @todo    Implement testGetChildren().
     */
    public function testGetChildren()
	{
        // Remove the following line when you implement this test.
        $this->markTestIncomplete('This test has not been implemented yet.');
	}
}
{% endif %}
