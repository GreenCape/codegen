<?php

namespace GreenCape\CodeGen\Tests\Unit;

use GreenCape\CodeGen\Definition\Type;

class TypeTest extends \PHPUnit\Framework\TestCase
{
    public function typeData()
    {
        return [
            'string-integer'  => [
                'integer',
                ['type' => 'integer'],
            ],
            'array-integer'  => [
                ['type' => 'integer'],
                ['type' => 'integer'],
            ],
            'string-string'   => [
                'string',
                ['type' => 'string'],
            ],
            'array-string'   => [
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
            'array-password' => [
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
            'array-richtext' => [
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
     */
    public function testType($config, $expected)
    {
        $type = new Type($config);

        foreach ($expected as $key => $value) {
            $this->assertEquals($value, $type->get($key));
        }
    }
}
