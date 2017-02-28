<?php

use Symfony\Component\Yaml\Yaml;

class SwaggerTest extends \PHPUnit\Framework\TestCase
{
    private $projectFile;

    private $templateDir;

    private $outputDir;

    public function setUp()
    {
        $basePath = dirname(__DIR__);

        $this->projectFile = $basePath . '/fixtures/project1.json';
        $this->templateDir = dirname($basePath) . '/src/templates/swagger.io';
        $this->outputDir = $basePath . '/tmp';
    }

    public function tearDown()
    {
        if (is_dir($this->outputDir)) {
            `rm -rf $this->outputDir`;
        }
    }

    public function testSwagger()
    {
        $project = new \GreenCape\CodeGen\Project($this->projectFile);
        $entityFile = dirname($this->projectFile) . '/entities/article.json';
        $project->addEntity(json_decode(file_get_contents($entityFile), true));

        (new \GreenCape\CodeGen\Generator())
            ->project($project)
            ->template($this->templateDir)
            ->output($this->outputDir)
            ->generate();

        $swagger = Yaml::parse(file_get_contents($this->outputDir . '/swagger.yml'));

        $this->assertEquals('Test Project 1', $swagger['info']['title']);
        $this->assertArrayHasKey('/articles', $swagger['paths']);
        $this->assertArrayHasKey('Article', $swagger['definitions']);
    }
}
