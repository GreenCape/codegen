<?php

namespace GreenCape\CodeGen\Tests\Unit\Fixture;

use GreenCape\CodeGen\Definition\ReadOnlyGuard;

/**
 * @property string getterProperty
 * @property string privateProperty
 * @property string protectedProperty
 */
class PropertyAccessFixture
{
    private $privateProperty = 'private';
    protected $protectedProperty = 'protected';
    public $publicProperty = 'public';

    use ReadOnlyGuard;

    /**
     * @return string
     */
    public function getGetterProperty(): string
    {
        return 'getter';
    }
}
