<?php

namespace GreenCape\CodeGen\Tests\Unit;

use GreenCape\CodeGen\Definition\Property;
use GreenCape\CodeGen\Definition\Type;
use PHPUnit\Framework\TestCase;
use Twig_Environment;
use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;
use Twig_Loader_Filesystem;

class TypeTest extends TestCase
{
    /**
     * @return array
     */
    public function typeData(): array
    {
        return [
            'string-integer'  => [
                'integer',
                ['type' => 'integer'],
            ],
            'array-integer'   => [
                ['type' => 'integer'],
                ['type' => 'integer'],
            ],
            'string-string'   => [
                'string',
                ['type' => 'string'],
            ],
            'array-string'    => [
                ['type' => 'string'],
                ['type' => 'string'],
            ],
            'string-password' => [
                'password',
                [
                    'type'  => 'string',
                    'input' => 'password',
                ],
            ],
            'array-password'  => [
                ['type' => 'password'],
                [
                    'type'  => 'string',
                    'input' => 'password',
                ],
            ],
            'string-richtext' => [
                'richtext',
                [
                    'type'  => 'string',
                    'input' => 'editor',
                ],
            ],
            'array-richtext'  => [
                ['type' => 'richtext'],
                [
                    'type'  => 'string',
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
     * @param array        $expected
     */
    public function testType($config, $expected): void
    {
        $type = new Type($config);

        foreach ($expected as $key => $value) {
            $this->assertEquals($value, $type->get($key));
        }
    }

    /**
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function testSelectOptionsAsStrings(): void
    {
        $property = new Property([
            'name'    => 'test_property',
            'type'    => 'select',
            'options' => [
                'foo',
                'bar',
            ],
        ]);
        $twig     = new Twig_Environment(new Twig_Loader_Filesystem(['tests/fixtures']));
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

    /**
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function testSelectOptionsAsArray(): void
    {
        $property = new Property([
            'name'    => 'test_property',
            'type'    => 'select',
            'options' => [
                [
                    'key'   => 'one',
                    'value' => 'foo',
                ],
                [
                    'key'   => 'two',
                    'value' => 'bar',
                ],
            ],
        ]);
        $twig     = new Twig_Environment(new Twig_Loader_Filesystem(['tests/fixtures']));
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

    /**
     * @return array
     */
    public function dataTypes(): array
    {
        return [
            [
                'boolean',
                [
                    'type'       => 'boolean',
                    'len'        => 1,
                    'input'      => 'yesno',
                    'role'       => '',
                    'index'      => '',
                    'validation' => [],
                ],
            ],
            [
                'csv',
                [
                    'type'       => 'string',
                    'len'        => 255,
                    'input'      => 'text',
                    'role'       => '',
                    'index'      => '',
                    'validation' => [],
                ],
            ],
            [
                'date',
                [
                    'type'       => 'date',
                    'len'        => 10,
                    'input'      => 'calendar',
                    'role'       => '',
                    'index'      => '',
                    'validation' => [],
                ],
            ],
            [
                'id',
                [
                    'type'       => 'integer',
                    'len'        => 10,
                    'input'      => 'none',
                    'role'       => 'key',
                    'index'      => 'unique',
                    'validation' => ['positive' => 1],
                ],
            ],
            [
                'integer',
                [
                    'type'       => 'integer',
                    'len'        => 10,
                    'input'      => 'number',
                    'role'       => '',
                    'index'      => '',
                    'validation' => [],
                ],
            ],
            [
                'json',
                [
                    'type'       => 'string',
                    'len'        => 255,
                    'input'      => 'text',
                    'role'       => '',
                    'index'      => '',
                    'validation' => [],
                ],
            ],
            [
                'password',
                [
                    'type'       => 'string',
                    'len'        => 64,
                    'input'      => 'password',
                    'role'       => '',
                    'index'      => '',
                    'validation' => [],
                ],
            ],
            [
                'richtext',
                [
                    'type'       => 'string',
                    'len'        => 4096,
                    'input'      => 'editor',
                    'role'       => '',
                    'index'      => '',
                    'validation' => [],
                ],
            ],
            [
                'select',
                [
                    'type'       => 'string',
                    'len'        => 64,
                    'input'      => 'select',
                    'role'       => '',
                    'index'      => '',
                    'validation' => [],
                ],
            ],
            [
                'string',
                [
                    'type'       => 'string',
                    'len'        => 255,
                    'input'      => 'text',
                    'role'       => '',
                    'index'      => '',
                    'validation' => [],
                ],
            ],
            [
                'title',
                [
                    'type'       => 'string',
                    'len'        => 64,
                    'input'      => 'text',
                    'role'       => 'title',
                    'index'      => 'unique',
                    'validation' => [],
                ],
            ],
            [
                'default',
                [
                    'type'       => 'default',
                    'len'        => 0,
                    'input'      => '',
                    'role'       => '',
                    'index'      => '',
                    'validation' => [],
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataTypes
     *
     * @param $declaredType
     * @param $expected
     *
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function testDefaults($declaredType, $expected): void
    {
        $property = new Property([
            'name' => 'test_property',
            'type' => $declaredType,
        ]);
        $twig     = new Twig_Environment(new Twig_Loader_Filesystem(['tests/fixtures']));
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
