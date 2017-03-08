<?php

use GreenCape\CodeGen\Project;

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

    public function testPropertiesAreNotWriteable()
    {
        $this->expectExceptionCode(1102);

        $project = new Project(json_decode(file_get_contents($this->templateDir . '/project1.json'), true));
        $project->name = 'new_name';
    }
}
