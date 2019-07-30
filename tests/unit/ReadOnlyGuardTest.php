<?php

namespace GreenCape\CodeGen\Tests\Unit;

use GreenCape\CodeGen\Tests\Unit\Fixture\PropertyAccessFixture;
use PHPUnit\Framework\TestCase;

class ReadOnlyGuardTest extends TestCase
{
    /**
     * @var PropertyAccessFixture
     */
    private $subject;

    public function setUp()
    {
        $this->subject = new PropertyAccessFixture();
    }

    /**
     * @testdox Properties with a getter can be read
     */
    public function testGetter(): void
    {
        $expected = 'getter';
        $actual   = $this->subject->getterProperty;

        $this->assertEquals($expected, $actual);
    }

    /**
     * @testdox Private properties can be read
     */
    public function testPrivateRead(): void
    {
        $expected = 'private';
        $actual   = $this->subject->privateProperty;

        $this->assertEquals($expected, $actual);
    }

    /**
     * @testdox Private properties cannot be written
     */
    public function testPrivateWrite(): void
    {
        $this->expectException(\RuntimeException::class);

        $this->subject->privateProperty = 'changed';
    }

    /**
     * @testdox Protected properties can be read
     */
    public function testProtectedRead(): void
    {
        $expected = 'protected';
        $actual   = $this->subject->protectedProperty;

        $this->assertEquals($expected, $actual);
    }

    /**
     * @testdox Protected properties cannot be written
     */
    public function testProtectedWrite(): void
    {
        $this->expectException(\RuntimeException::class);

        $this->subject->privateProperty = 'changed';
    }

    /**
     * @testdox Public properties are not affected
     */
    public function testPublic(): void
    {
        $expected = 'public';
        $actual   = $this->subject->publicProperty;

        $this->assertEquals($expected, $actual);

        $changed                           = 'changed';
        $this->subject->publicProperty = $changed;
        $actual = $this->subject->publicProperty;

        $this->assertEquals($changed, $actual);
    }
}
