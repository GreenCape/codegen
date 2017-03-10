<?php

use GreenCape\CodeGen\Definition\Entity;
use GreenCape\CodeGen\Definition\Registry;

class RegistryTest extends \PHPUnit\Framework\TestCase
{
    public function testCallsForUnregisteredEntitiesAreStashed()
    {
        $called = 0;

        $registry = new Registry();
        $registry->registerCallback('Foo', function (Entity $entity) use (&$called) {
            $called++;
        });

        $this->assertEquals(0, $called, 'Callback should not have been called yet.');

        $registry->registerEntity(new Entity(['name' => 'Foo'], $registry));

        $this->assertEquals(1, $called, 'Callback should have been called now.');
    }

    public function testCallsForRegisteredEntitiesAreFulfilledImmediately()
    {
        $called = 0;

        $registry = new Registry();
        $registry->registerEntity(new Entity(['name' => 'Foo'], $registry));

        $this->assertEquals(0, $called, 'Callback should not have been called yet.');

        $registry->registerCallback('Foo', function (Entity $entity) use (&$called) {
            $called++;
        });

        $this->assertEquals(1, $called, 'Callback should have been called now.');
    }

    /**
     * @testdox Callback for registerCallback() can be a closure
     */
    public function testCallbackLambda()
    {
        $called = 0;

        $registry = new Registry();
        $registry->registerEntity(new Entity(['name' => 'Foo'], $registry));
        $registry->registerCallback('Foo', function (Entity $entity) use (&$called) {
            $called++;
            $this->assertInstanceOf(Entity::class, $entity, 'Callback shoud be provided with the entity.');
        });

        $this->assertEquals(1, $called, 'Callback should have been called.');
    }

    private $called;

    /**
     * @testdox Callback for registerCallback() with a closure can change private object properties
     */
    public function testCallbackCanChangePrivateObjectProperty()
    {
        $this->called = 0;

        $registry = new Registry();
        $registry->registerEntity(new Entity(['name' => 'Foo'], $registry));
        $registry->registerCallback('Foo', function (Entity $entity) {
            $this->called++;
        });

        $this->assertEquals(1, $this->called, 'Callback should have been called.');
    }

    public function callbackMethod(Entity $entity)
    {
        $this->called++;
        $this->assertInstanceOf(Entity::class, $entity, 'Callback shoud be provided with the entity.');
    }

    /**
     * @testdox Callback for registerCallback() can be a method of $this
     */
    public function testCallbackObjectMethod()
    {
        $this->called = 0;

        $registry = new Registry();
        $registry->registerEntity(new Entity(['name' => 'Foo'], $registry));
        $registry->registerCallback('Foo', [$this, 'callbackMethod']);

        $this->assertEquals(1, $this->called, 'Callback should have been called.');
    }
}
