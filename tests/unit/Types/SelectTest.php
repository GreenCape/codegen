<?php

namespace GreenCape\CodeGen\Tests\Unit;

use GreenCape\CodeGen\Definition\Property;

class SelectTest extends \PHPUnit\Framework\TestCase
{
    public function testOptionsAsStrings()
    {
        $property = new Property([
            'name' => 'test_property',
            'type' => 'select',
            'options' => [
                'foo',
                'bar',
            ]
        ]);
        $twig     = new \Twig_Environment(new \Twig_Loader_Filesystem(['tests/fixtures']));
        $rendered = $twig->render('dumpProperty.json', ['property' => $property]);
        $result   = json_decode($rendered, true);

        $this->assertEquals('test_property', $result['name']);
        $this->assertEquals('select', $result['type']);
        $this->assertEquals([
            'foo' => 'foo',
            'bar' => 'bar',
        ], $result['options']);
    }

    public function testOptionsAsArray()
    {
        $property = new Property([
            'name'    => 'test_property',
            'type'    => 'select',
            'options' => [
                [
                    'key' => 'one',
                    'value' => 'foo',
                ],
                [
                    'key'   => 'two',
                    'value' => 'bar',
                ],
            ]
        ]);
        $twig     = new \Twig_Environment(new \Twig_Loader_Filesystem(['tests/fixtures']));
        $rendered = $twig->render('dumpProperty.json', ['property' => $property]);
        $result   = json_decode($rendered, true);

        $this->assertEquals('test_property', $result['name']);
        $this->assertEquals('select', $result['type']);
        $this->assertEquals([
            'one' => 'foo',
            'two' => 'bar',
        ], $result['options']);
    }
}
