<?php

namespace GreenCape\CodeGen\Tests\Unit;

use GreenCape\CodeGen\Definition\Entity;
use GreenCape\CodeGen\Definition\Registry;
use GreenCape\CodeGen\Definition\Relation;

class RelationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox An exception (1204) is thrown when type is not specified
     */
    public function testMissingType()
    {
        $this->expectExceptionCode(1204);

        $config   = ['name' => 'test'];
        $entity   = new Entity(['name' => 'Entity']);
        $relation = new Relation($config, $entity, new Registry());
    }

    /**
     * @testdox Property is set to entity's key if not specified
     */
    public function testPropertyDefault()
    {
        $config   = ['name' => 'test', 'type' => 'hasManyThru', 'entity' => 'Foreign'];
        $entity   = new Entity(['name' => 'Entity', 'properties' => [['name' => 'entity_id', 'role' => 'key']]]);
        $relation = new Relation($config, $entity, new Registry());

        $this->assertEquals('entity_id', $relation->getProperty()
                                                  ->getName());
    }
}
