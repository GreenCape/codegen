<?php

namespace GreenCape\CodeGen\Tests\Unit;

use Exception;
use GreenCape\CodeGen\Definition\Entity;
use GreenCape\CodeGen\Definition\Registry;
use PHPUnit\Framework\TestCase;

class RegistryTest extends TestCase
{
    private $called;

    /**
     * @throws Exception
     */
    public function testCallsForUnregisteredEntitiesAreStashed(): void
    {
        $called = 0;

        $registry = new Registry();
        $registry->registerCallback('Foo', static function (/** @noinspection PhpUnusedParameterInspection */ Entity $entity) use (&$called) {
            $called++;
        });

        $this->assertEquals(0, $called, 'Callback should not have been called yet.');

        $registry->registerEntity(new Entity(['name' => 'Foo'], $registry));

        $this->assertEquals(1, $called, 'Callback should have been called now.');
    }

    /**
     * @throws Exception
     */
    public function testCallsForRegisteredEntitiesAreFulfilledImmediately(): void
    {
        $called = 0;

        $registry = new Registry();
        $registry->registerEntity(new Entity(['name' => 'Foo'], $registry));

        $this->assertEquals(0, $called, 'Callback should not have been called yet.');

        $registry->registerCallback('Foo', static function (/** @noinspection PhpUnusedParameterInspection */ Entity $entity) use (&$called) {
            $called++;
        });

        $this->assertEquals(1, $called, 'Callback should have been called now.');
    }

    /**
     * @testdox Callback for registerCallback() can be a closure
     * @throws Exception
     */
    public function testCallbackLambda(): void
    {
        $called = 0;

        $registry = new Registry();
        $registry->registerEntity(new Entity(['name' => 'Foo'], $registry));
        $registry->registerCallback('Foo', function (Entity $entity) use (&$called) {
            $called++;
            $this->assertInstanceOf(Entity::class, $entity, 'Callback should be provided with the entity.');
        });

        $this->assertEquals(1, $called, 'Callback should have been called.');
    }

    /**
     * @testdox Callback for registerCallback() with a closure can change private object properties
     * @throws Exception
     */
    public function testCallbackCanChangePrivateObjectProperty(): void
    {
        $this->called = 0;

        $registry = new Registry();
        $registry->registerEntity(new Entity(['name' => 'Foo'], $registry));
        $registry->registerCallback('Foo', function (/** @noinspection PhpUnusedParameterInspection */ Entity $entity) {
            $this->called++;
        });

        $this->assertEquals(1, $this->called, 'Callback should have been called.');
    }

    /**
     * @param Entity $entity
     */
    public function callbackMethod(Entity $entity): void
    {
        $this->called++;
        $this->assertInstanceOf(Entity::class, $entity, 'Callback should be provided with the entity.');
    }

    /**
     * @testdox Callback for registerCallback() can be a method of $this
     * @throws Exception
     */
    public function testCallbackObjectMethod(): void
    {
        $this->called = 0;

        $registry = new Registry();
        $registry->registerEntity(new Entity(['name' => 'Foo'], $registry));
        $registry->registerCallback('Foo', [$this, 'callbackMethod']);

        $this->assertEquals(1, $this->called, 'Callback should have been called.');
    }
}
