<?php

namespace GreenCape\CodeGen\Tests\Unit;

use GreenCape\CodeGen\Definition\Validation;
use PHPUnit\Framework\TestCase;

class ValidationTest extends TestCase
{
    /**
     * @return array
     */
    public function validationData(): array
    {
        return [
            'string'       => [
                'positive',
                [
                    'rule'  => 'positive',
                    'value' => true,
                ],
            ],
            'key => value' => [
                ['length' => 64],
                [
                    'rule'  => 'length',
                    'value' => 64,
                ],
            ],
            'rule/value'   => [
                ['rule'  => 'length',
                 'value' => 64,
                ],
                [
                    'rule'  => 'length',
                    'value' => 64,
                ],
            ],
        ];
    }

    /**
     * @dataProvider validationData
     *
     * @param $arg
     * @param $expected
     */
    public function testValidation($arg, $expected): void
    {
        $validation = new Validation($arg);

        $this->assertEquals($expected['rule'], $validation->getRule());
        $this->assertEquals($expected['value'], $validation->getValue());
    }
}
