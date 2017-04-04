<?php

namespace GreenCape\CodeGen\Tests\Unit;

use GreenCape\CodeGen\Definition\Validation;

class ValidationTest extends \PHPUnit\Framework\TestCase
{
    public function validationData()
    {
        return [
            'string' => [
                'positive',
                ['rule' => 'positive', 'value' => true],
            ],
            'key => value' => [
                ['length' => 64],
                ['rule' => 'length', 'value' => 64],
            ],
            'rule/value' => [
                ['rule' => 'length', 'value' => 64],
                ['rule' => 'length', 'value' => 64],
            ],
        ];
    }

    /**
     * @dataProvider validationData
     *
     * @testdox
     */
    public function testValidation($arg, $expected)
    {
        $validation = new Validation($arg);

        $this->assertEquals($expected['rule'], $validation->getRule());
        $this->assertEquals($expected['value'], $validation->getValue());
    }
}
