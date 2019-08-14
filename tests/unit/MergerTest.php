<?php

namespace GreenCape\CodeGen\Tests\Unit;

use Exception;
use GreenCape\CodeGen\Definition\Merger;
use GreenCape\CodeGen\Definition\Project;
use PHPUnit\Framework\TestCase;

class MergerTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testMerge()
    {
        $projectDefinition = json_decode(file_get_contents('tests/fixtures/feature1.json'), true);
        $featureDefinition = json_decode(file_get_contents('tests/fixtures/feature2.json'), true);

        $merger = new Merger($projectDefinition);
        $merger->merge($featureDefinition);

        $project = new Project($merger->definition());
        $entity  = $project->aggregateRoot;

        $this->assertArrayHasKey('catid', $entity->properties);
    }
}
