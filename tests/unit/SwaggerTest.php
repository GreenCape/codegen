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

    /**
     * @testdox swagger-codegen-cli generate - Generate code with chosen lang
     */
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

    /**
     * @testdox swagger-codegen-cli config-help - Config help for chosen lang
     */
    public function testConfigHelp()
    {
        $swagger = new Swagger();

        $result = $swagger->configHelp('php');

        $this->assertContains('CONFIG OPTIONS', $result);
    }

    /**
     * @testdox swagger-codegen-cli help - Display help information
     */
    public function testHelp()
    {
        $swagger = new Swagger();

        $result = implode("\n", $swagger->help());

        $this->assertContains('usage: swagger-codegen-cli', $result);
    }

    /**
     * @testdox swagger-codegen-cli help <command> - Display help information for a command
     */
    public function testHelpWithParam()
    {
        $swagger = new Swagger();

        $result = implode("\n", $swagger->help('generate'));

        $this->assertContains('swagger-codegen-cli generate', $result);
    }

    /**
     * @testdox swagger-codegen-cli langs - Shows available langs
     */
    public function testLanguages()
    {
        $swagger = new Swagger();

        $result = $swagger->languages();

        $this->assertContains('html2', $result);
    }

    /**
     * @testdox swagger-codegen-cli meta - MetaGenerator
     */
    public function testMeta()
    {
        $swagger = new Swagger();

        $result = $swagger->meta('-o /local/tests/tmp/generated');

        $this->assertFileExists('tests/tmp/generated/pom.xml');

        `rm -rf tests/tmp/generated`;
    }

    /**
     * @testdox swagger-codegen-cli version - Show version information
     */
    public function testVersion()
    {
        $swagger = new Swagger();

        $result = $swagger->version();

        $this->assertRegExp('~^\d+\.\d+\.\d+~', $result);
    }
}
