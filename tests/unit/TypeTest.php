<?php

namespace GreenCape\CodeGen\Tests\Unit;

use GreenCape\CodeGen\Definition\Type;

class TypeTest extends \PHPUnit\Framework\TestCase
{
    public function typeData()
    {
        return [
            'integer'  => [
                ['type' => 'integer'],
                ['type' => 'integer'],
            ],
            'string'   => [
                ['type' => 'string'],
                ['type' => 'string'],
            ],
            'password' => [
                ['type' => 'password'],
                [
                    'type'  => 'string',
                    'input' => 'password',
                ],
            ],
            'richtext' => [
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
