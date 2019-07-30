<?php

namespace GreenCape\CodeGen\Tests\Unit;

use GreenCape\CodeGen\Inflector;
use PHPUnit\Framework\TestCase;

class InflectorTest extends TestCase
{
    /** @var  Inflector */
    private $inflector;

    /** @var array */
    private $testData = [
        'title'     => 'Test Data',
        'variable'  => 'testData',
        'class'     => 'TestData',
        'table'     => 'test_data',
        'dash'      => 'test-data',
        'constant'  => 'TEST_DATA',
        'namespace' => 'Test\\Data',
    ];

    public function setUp()
    {
        $this->inflector = new Inflector();
    }

    /**
     * @return array
     */
    public function inflectionData(): array
    {
        $cases = [];
        foreach ($this->testData as $key => $value) {
            $cases[$key] = [
                $key,
                $value,
            ];
        }

        return $cases;
    }

    /**
     * @dataProvider inflectionData
     *
     * @param $filter
     * @param $expected
     */
    public function testConvertFromTitleTo($filter, $expected): void
    {
        $this->assertEquals($expected, $this->inflector->apply($filter, $this->testData['title']));
    }

    /**
     * @dataProvider inflectionData
     *
     * @param $filter
     * @param $expected
     */
    public function testConvertFromVariableTo($filter, $expected): void
    {
        $this->assertEquals($expected, $this->inflector->apply($filter, $this->testData['variable']));
    }

    /**
     * @dataProvider inflectionData
     *
     * @param $filter
     * @param $expected
     */
    public function testConvertFromClassTo($filter, $expected): void
    {
        $this->assertEquals($expected, $this->inflector->apply($filter, $this->testData['class']));
    }

    /**
     * @dataProvider inflectionData
     *
     * @param $filter
     * @param $expected
     */
    public function testConvertFromTableTo($filter, $expected): void
    {
        $this->assertEquals($expected, $this->inflector->apply($filter, $this->testData['table']));
    }

    /**
     * @dataProvider inflectionData
     *
     * @param $filter
     * @param $expected
     */
    public function testConvertFromDashTo($filter, $expected): void
    {
        $this->assertEquals($expected, $this->inflector->apply($filter, $this->testData['dash']));
    }

    /**
     * @dataProvider inflectionData
     *
     * @param $filter
     * @param $expected
     */
    public function testConvertFromNamespaceTo($filter, $expected): void
    {
        $this->assertEquals($expected, $this->inflector->apply($filter, $this->testData['namespace']));
    }

    /**
     * @return array
     */
    public function numerationData(): array
    {
        return [
            [
                'person',
                'people',
            ],
            [
                'test',
                'tests',
            ],
            [
                'entity',
                'entities',
            ],
        ];
    }

    /**
     * @dataProvider numerationData
     *
     * @param string $singular
     * @param string $plural
     */
    public function testSingular(string $singular, string $plural): void
    {
        $this->assertEquals($singular, $this->inflector->singular($plural));
    }

    /**
     * @dataProvider numerationData
     *
     * @param string $singular
     * @param string $plural
     */
    public function testPlural(string $singular, string $plural): void
    {
        $this->assertEquals($plural, $this->inflector->plural($singular));
    }
}
