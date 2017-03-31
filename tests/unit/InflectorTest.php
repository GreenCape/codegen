<?php

namespace GreenCape\CodeGen\Tests\Unit;

use GreenCape\CodeGen\Inflector;

class InflectorTest extends \PHPUnit\Framework\TestCase
{
    /** @var  Inflector */
    private $inflector;

    private $testData = ['title' => 'Test Data', 'variable' => 'testData', 'class' => 'TestData', 'table' => 'test_data', 'dash' => 'test-data', 'constant' => 'TEST_DATA',];

    public function setUp()
    {
        $this->inflector = new Inflector();
    }

    public function inflectionData()
    {
        $cases = [];
        foreach ($this->testData as $key => $value) {
            $cases[$key] = [$key, $value];
        }

        return $cases;
    }

    /**
     * @dataProvider inflectionData
     *
     * @param $filter
     * @param $expected
     */
    public function testConvertFromTitleTo($filter, $expected)
    {
        $this->assertEquals($expected, $this->inflector->apply($filter, $this->testData['title']));
    }

    /**
     * @dataProvider inflectionData
     *
     * @param $filter
     * @param $expected
     */
    public function testConvertFromVariableTo($filter, $expected)
    {
        $this->assertEquals($expected, $this->inflector->apply($filter, $this->testData['variable']));
    }

    /**
     * @dataProvider inflectionData
     *
     * @param $filter
     * @param $expected
     */
    public function testConvertFromClassTo($filter, $expected)
    {
        $this->assertEquals($expected, $this->inflector->apply($filter, $this->testData['class']));
    }

    /**
     * @dataProvider inflectionData
     *
     * @param $filter
     * @param $expected
     */
    public function testConvertFromTableTo($filter, $expected)
    {
        $this->assertEquals($expected, $this->inflector->apply($filter, $this->testData['table']));
    }

    /**
     * @dataProvider inflectionData
     *
     * @param $filter
     * @param $expected
     */
    public function testConvertFromDashTo($filter, $expected)
    {
        $this->assertEquals($expected, $this->inflector->apply($filter, $this->testData['dash']));
    }

    public function numerationData()
    {
        return [['person', 'people'], ['test', 'tests'], ['entity', 'entities'],];
    }

    /**
     * @dataProvider numerationData
     *
     * @param $singular
     * @param $plural
     */
    public function testSingular($singular, $plural)
    {
        $this->assertEquals($singular, $this->inflector->singular($plural));
    }

    /**
     * @dataProvider numerationData
     *
     * @param $singular
     * @param $plural
     */
    public function testPlural($singular, $plural)
    {
        $this->assertEquals($plural, $this->inflector->plural($singular));
    }
}
