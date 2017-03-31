<?php

namespace GreenCape\CodeGen\Tests\Unit;

use GreenCape\CodeGen\Definition\Project;
use GreenCape\CodeGen\Swagger;

class SwaggerTest extends \PHPUnit\Framework\TestCase
{
    private $projectFile;

    private $templateDir;

    private $outputDir;

    public function setUp()
    {
        $basePath = dirname(__DIR__);

        $this->projectFile = $basePath . '/fixtures/project2.json';
        $this->templateDir = dirname($basePath) . '/src/templates/swagger.io';
        $this->outputDir   = $basePath . '/tmp/swagger.io';

        $project = new Project(json_decode(file_get_contents($this->projectFile), true));

        (new \GreenCape\CodeGen\Generator())->project($project)
                                            ->template($this->templateDir)
                                            ->output($this->outputDir)
                                            ->generate()
        ;
    }

    public function tearDown()
    {
        if (is_dir($this->outputDir)) {
            #`rm -rf $this->outputDir`;
        }
    }

    public function testSwaggerGenerator()
    {
        $swagger = new Swagger();

        if (!file_exists('tests/tmp/swagger.io/api-doc')) {
            mkdir('tests/tmp/swagger.io/api-doc');
        }
        $swagger->generate('-i /local/tests/tmp/swagger.io/swagger.yml -l html2 -o /local/tests/tmp/swagger.io/api-doc');

        $this->assertFileExists('tests/tmp/swagger.io/api-doc/index.html');
        $this->assertEquals(getmyuid(), fileowner('tests/tmp/swagger.io/api-doc/index.html'));
    }
}
