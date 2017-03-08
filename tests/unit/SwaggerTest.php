<?php

use GreenCape\CodeGen\Entity;
use GreenCape\CodeGen\Project;
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
        $this->outputDir = $basePath . '/tmp/swagger.io';
    }

    public function tearDown()
    {
        if (is_dir($this->outputDir)) {
            #`rm -rf $this->outputDir`;
        }
    }

    /**
     * @testdox OpenAPI 2 template compiles
     */
    public function testSwagger()
    {
        $project = new Project(json_decode(file_get_contents($this->projectFile), true));
        $project->addEntity(new Entity(json_decode(file_get_contents(dirname($this->projectFile) . '/entities/article.json'), true)));

        (new \GreenCape\CodeGen\Generator())
            ->project($project)
            ->template($this->templateDir)
            ->output($this->outputDir)
            ->generate();

        $swagger = Yaml::parse(file_get_contents($this->outputDir . '/swagger.yml'));

        $this->assertEquals('First Test Project', $swagger['info']['title']);
        $this->assertArrayHasKey('/articles', $swagger['paths']);
        $this->assertArrayHasKey('Article', $swagger['definitions']);
    }
}
