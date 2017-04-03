<?php

namespace GreenCape\CodeGen\Tests\Unit;

use GreenCape\CodeGen\Definition\Project;

class ProjectTest extends \PHPUnit\Framework\TestCase
{
    private $templateDir;

    public function setUp()
    {
        $basePath = dirname(__DIR__);

        $this->templateDir = $basePath . '/fixtures';
    }

    public function testPropertiesAreReadable()
    {
        $project = new Project(json_decode(file_get_contents($this->templateDir . '/project1.json'), true));

        $this->assertEquals('test_project', $project->name);
        $this->assertEquals(1, count($project->authors));
    }

    /**
     * @testdox An exception (9001) is thrown on write attempt to properties
     */
    public function testPropertiesAreNotWriteable()
    {
        $this->expectExceptionCode(9001);

        $project       = new Project(json_decode(file_get_contents($this->templateDir . '/project1.json'), true));
        $project->name = 'new_name';
    }

    /**
     * @testdox An exception (1101) is thrown if name and title are missing
     */
    public function testMissingNameAndTitle()
    {
        $this->expectExceptionCode(1101);

        $project       = new Project(json_decode(file_get_contents($this->templateDir . '/project-missing-name-and-title.json'), true));
    }

    /**
     * @testdox Title is generated from name if missing
     */
    public function testMissingTitle()
    {
        $project = new Project(json_decode(file_get_contents($this->templateDir . '/project-missing-title.json'), true));

        $this->assertEquals('Test Project', $project->getTitle());
    }

    /**
     * @testdox Name is generated from title if missing
     */
    public function testMissingName()
    {
        $project = new Project(json_decode(file_get_contents($this->templateDir . '/project-missing-name.json'), true));

        $this->assertEquals('first_test_project', $project->getName());
    }
}
