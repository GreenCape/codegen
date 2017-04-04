<?php

namespace GreenCape\CodeGen\Tests\Unit;

use GreenCape\CodeGen\Definition\Property;
use GreenCape\CodeGen\Definition\Type;

class TypeTest extends \PHPUnit\Framework\TestCase
{
    public function typeData()
    {
        return [
            'string-integer' => [
                'integer',
                ['type' => 'integer'],
            ],
            'array-integer' => [
                ['type' => 'integer'],
                ['type' => 'integer'],
            ],
            'string-string' => [
                'string',
                ['type' => 'string'],
            ],
            'array-string' => [
                ['type' => 'string'],
                ['type' => 'string'],
            ],
            'string-password' => [
                'password',
                [
                    'type' => 'string',
                    'input' => 'password',
                ],
            ],
            'array-password' => [
                ['type' => 'password'],
                [
                    'type' => 'string',
                    'input' => 'password',
                ],
            ],
            'string-richtext' => [
                'richtext',
                [
                    'type' => 'string',
                    'input' => 'editor',
                ],
            ],
            'array-richtext' => [
                ['type' => 'richtext'],
                [
                    'type' => 'string',
                    'input' => 'editor',
                ],
            ],
        ];
    }

    /**
     * @dataProvider typeData
     *
     * @testdox
     *
     * @param string|array $config
     * @param array $expected
     */
    public function testType($config, $expected)
    {
        $type = new Type($config);

        foreach ($expected as $key => $value) {
            $this->assertEquals($value, $type->get($key));
        }
    }

    public function testSelectOptionsAsStrings()
    {
        $property = new Property([
            'name' => 'test_property',
            'type' => 'select',
            'options' => [
                'foo',
                'bar',
            ],
        ]);
        $twig     = new \Twig_Environment(new \Twig_Loader_Filesystem(['tests/fixtures']));
        $rendered = $twig->render('dumpProperty.json', ['property' => $property]);
        $result   = json_decode($rendered, true);

        $this->assertEquals('test_property', $result['name']);
        $this->assertEquals('string', $result['type']);
        $this->assertEquals('select', $result['input']);
        $this->assertEquals([
            'foo' => 'foo',
            'bar' => 'bar',
        ], $result['options']);
    }

    public function testSelectOptionsAsArray()
    {
        $property = new Property([
            'name' => 'test_property',
            'type' => 'select',
            'options' => [
                [
                    'key' => 'one',
                    'value' => 'foo',
                ],
                [
                    'key' => 'two',
                    'value' => 'bar',
                ],
            ],
        ]);
        $twig     = new \Twig_Environment(new \Twig_Loader_Filesystem(['tests/fixtures']));
        $rendered = $twig->render('dumpProperty.json', ['property' => $property]);
        $result   = json_decode($rendered, true);

        $this->assertEquals('test_property', $result['name']);
        $this->assertEquals('string', $result['type']);
        $this->assertEquals('select', $result['input']);
        $this->assertEquals([
            'one' => 'foo',
            'two' => 'bar',
        ], $result['options']);
    }

    public function dataTypes()
    {
        return [
            [
                'boolean',
                [
                    'type' => 'boolean',
                    'len' => 1,
                    'input' => 'yesno',
                    'role' => '',
                    'index' => '',
                    'validation' => [],
                ],
            ],
            [
                'csv',
                [
                    'type' => 'string',
                    'len' => 255,
                    'input' => 'text',
                    'role' => '',
                    'index' => '',
                    'validation' => [],
                ],
            ],
            [
                'date',
                [
                    'type' => 'date',
                    'len' => 10,
                    'input' => 'calendar',
                    'role' => '',
                    'index' => '',
                    'validation' => [],
                ],
            ],
            [
                'id',
                [
                    'type' => 'integer',
                    'len' => 10,
                    'input' => 'none',
                    'role' => 'key',
                    'index' => 'unique',
                    'validation' => ['positive' => 1],
                ],
            ],
            [
                'integer',
                [
                    'type' => 'integer',
                    'len' => 10,
                    'input' => 'number',
                    'role' => '',
                    'index' => '',
                    'validation' => [],
                ],
            ],
            [
                'json',
                [
                    'type' => 'string',
                    'len' => 255,
                    'input' => 'text',
                    'role' => '',
                    'index' => '',
                    'validation' => [],
                ],
            ],
            [
                'password',
                [
                    'type' => 'string',
                    'len' => 64,
                    'input' => 'password',
                    'role' => '',
                    'index' => '',
                    'validation' => [],
                ],
            ],
            [
                'richtext',
                [
                    'type' => 'string',
                    'len' => 4096,
                    'input' => 'editor',
                    'role' => '',
                    'index' => '',
                    'validation' => [],
                ],
            ],
            [
                'select',
                [
                    'type' => 'string',
                    'len' => 64,
                    'input' => 'select',
                    'role' => '',
                    'index' => '',
                    'validation' => [],
                ],
            ],
            [
                'string',
                [
                    'type' => 'string',
                    'len' => 255,
                    'input' => 'text',
                    'role' => '',
                    'index' => '',
                    'validation' => [],
                ],
            ],
            [
                'title',
                [
                    'type' => 'string',
                    'len' => 64,
                    'input' => 'text',
                    'role' => 'title',
                    'index' => 'unique',
                    'validation' => [],
                ],
            ],
            [
                'default',
                [
                    'type' => 'default',
                    'len' => 0,
                    'input' => '',
                    'role' => '',
                    'index' => '',
                    'validation' => [],
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataTypes
     */
    public function testDefaults($declaredType, $expected)
    {
        $property = new Property([
            'name' => 'test_property',
            'type' => $declaredType,
        ]);
        $twig     = new \Twig_Environment(new \Twig_Loader_Filesystem(['tests/fixtures']));
        $rendered = $twig->render('dumpProperty.json', ['property' => $property]);
        $result   = json_decode($rendered, true);

        $this->assertEquals('test_property', $result['name']);
        $this->assertEquals($expected['type'], $result['type']);
        $this->assertEquals($expected['len'], $result['len']);
        $this->assertEquals($expected['role'], $result['role']);
        $this->assertEquals($expected['index'], $result['index']);
        $this->assertEquals($expected['input'], $result['input']);
        $this->assertEquals($expected['validation'], $result['validation']);
    }
}
