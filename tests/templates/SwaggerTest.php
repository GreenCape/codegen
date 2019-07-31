<?php

namespace GreenCape\CodeGen\Tests\Templates;

use Exception;
use GreenCape\CodeGen\Definition\Project;
use GreenCape\CodeGen\Generator;
use GreenCape\CodeGen\Swagger;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

class SwaggerTest extends TestCase
{
    private $projectFile;

    private $templateDir;

    private $outputDir;

    public function setUp()
    {
        $basePath = dirname(__DIR__);

        $this->projectFile = $basePath . '/fixtures/all_relations_project.json';
        $this->templateDir = dirname($basePath) . '/src/templates/swagger.io';
        $this->outputDir   = $basePath . '/tmp/swagger.io';
    }

    public function tearDown()
    {
        if (is_dir($this->outputDir)) {
            shell_exec("rm -rf {$this->outputDir}");
        }
    }

    /**
     * @testdox OpenAPI 3 template compiles
     * @throws Exception
     */
    public function testSwagger(): void
    {
        $project = new Project(json_decode(file_get_contents($this->projectFile), true));

        (new Generator())->project($project)
                         ->template($this->templateDir)
                         ->output($this->outputDir)
                         ->generate()
        ;

        $swagger = Yaml::parse(file_get_contents($this->outputDir . '/swagger.yml'));

        $this->assertEquals('Test Project with Relations', $swagger['info']['title']);
        $this->assertArrayHasKey('/articles', $swagger['paths']);
        $this->assertArrayHasKey('Article', $swagger['definitions']);
    }

    /**
     * @testdox The generated YAML file is valid against the OpenAPI 3.0 schema and spec
     */
    public function testValidity(): void
    {
        $swagger = new Swagger();

        $output = $swagger->validate('tests/tmp/swagger.io/swagger.yml');

        $this->assertTrue($output);
    }
}
