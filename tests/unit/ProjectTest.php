<?php

namespace GreenCape\CodeGen\Tests\Unit;

use Exception;
use GreenCape\CodeGen\Definition\Project;
use PHPUnit\Framework\TestCase;

class ProjectTest extends TestCase
{
    private $templateDir;

    public function setUp()
    {
        $basePath = dirname(__DIR__);

        $this->templateDir = $basePath . '/fixtures';
    }

    /**
     * @throws Exception
     */
    public function testPropertiesAreReadable(): void
    {
        $project = new Project(json_decode(file_get_contents($this->templateDir . '/project1.json'), true));

        $this->assertEquals('test_project', $project->name);
        $this->assertCount(1, $project->authors);
    }

    /**
     * @testdox An exception (9001) is thrown on write attempt to properties
     * @throws Exception
     */
    public function testPropertiesAreNotWritable(): void
    {
        $this->expectExceptionCode(9001);

        $project       = new Project(json_decode(file_get_contents($this->templateDir . '/project1.json'), true));
        $project->name = 'new_name';
    }

    /**
     * @testdox An exception (1101) is thrown if name and title are missing
     * @throws Exception
     */
    public function testMissingNameAndTitle(): void
    {
        $this->expectExceptionCode(1101);

        new Project(json_decode(file_get_contents($this->templateDir . '/project-missing-name-and-title.json'), true));
    }

    /**
     * @testdox Title is generated from name if missing
     * @throws Exception
     */
    public function testMissingTitle(): void
    {
        $project = new Project(json_decode(file_get_contents($this->templateDir . '/project-missing-title.json'), true));

        $this->assertEquals('Test Project', $project->getTitle());
    }

    /**
     * @testdox Name is generated from title if missing
     * @throws Exception
     */
    public function testMissingName(): void
    {
        $project = new Project(json_decode(file_get_contents($this->templateDir . '/project-missing-name.json'), true));

        $this->assertEquals('first_test_project', $project->getName());
    }
}
