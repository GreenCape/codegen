<?php

namespace GreenCape\CodeGen\Tests\Unit;

use GreenCape\CodeGen\Definition\Validation;

class ValidationTest extends \PHPUnit\Framework\TestCase
{
    public function validationData()
    {
        return [
            'string without value' => [
                ['positive', null],
                ['rule' => 'positive', 'value' => null],
            ],
            'string with value' => [
                ['length', 64],
                ['rule' => 'length', 'value' => 64],
            ],
            'array' => [
                [['rule' => 'length', 'value' => 64], null],
                ['rule' => 'length', 'value' => 64],
            ],
        ];
    }

    /**
     * @dataProvider validationData
     *
     * @testdox
     */
    public function testValidation($args, $expected)
    {
        $validation = new Validation($args[0], $args[1]);

        $this->assertEquals($expected['rule'], $validation->getRule());
        $this->assertEquals($expected['value'], $validation->getValue());
    }
}
