<?php

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
        $project = new \GreenCape\CodeGen\Project($this->templateDir . '/project1.json');

        $this->assertEquals('test_project_1', $project->name);
        $this->assertEquals(1, count($project->authors));
    }

    public function testPropertiesAreWriteable()
    {
        $project = new \GreenCape\CodeGen\Project($this->templateDir . '/project1.json');
        $project->name = 'new_name';

        $this->assertEquals('new_name', $project->name);
    }

    public function testPropertiesUseGetterAndSetterIfAvailable()
    {
        $project = new \GreenCape\CodeGen\Project($this->templateDir . '/project1.json');

        $project->entities = ['foo'];
        $project->addEntity('bar');

        $this->assertEquals(['foo', 'bar'], $project->entities);
    }
}
