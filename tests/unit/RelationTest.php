<?php

namespace GreenCape\CodeGen\Tests\Unit;

use Exception;
use GreenCape\CodeGen\Definition\Entity;
use GreenCape\CodeGen\Definition\Registry;
use GreenCape\CodeGen\Definition\Relation;
use PHPUnit\Framework\TestCase;

class RelationTest extends TestCase
{
    /**
     * @testdox An exception (1204) is thrown when type is not specified
     * @throws Exception
     */
    public function testMissingType(): void
    {
        $this->expectExceptionCode(1204);

        $config = ['name' => 'test'];
        $entity = new Entity(['name' => 'Entity']);

        new Relation($config, $entity, new Registry());
    }

    /**
     * @testdox Property is set to entity's key if not specified
     * @throws Exception
     */
    public function testPropertyDefault(): void
    {
        $config   = [
            'name'   => 'test',
            'type'   => 'hasManyThru',
            'entity' => 'Foreign',
        ];
        $entity   = new Entity([
            'name'       => 'Entity',
            'properties' => [
                [
                    'name' => 'entity_id',
                    'role' => 'key',
                ],
            ],
        ]);
        $relation = new Relation($config, $entity, new Registry());

        $this->assertEquals('entity_id', $relation->getProperty()
                                                  ->getName());
    }
}
