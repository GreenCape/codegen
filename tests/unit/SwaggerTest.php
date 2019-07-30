<?php

namespace GreenCape\CodeGen\Tests\Unit;

use Exception;
use GreenCape\CodeGen\Definition\Project;
use GreenCape\CodeGen\Generator;
use GreenCape\CodeGen\Swagger;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class SwaggerTest extends TestCase
{
    private $projectFile;

    private $templateDir;

    private $outputDir;

    /**
     * @throws Exception
     */
    public function setUp()
    {
        $basePath = dirname(__DIR__);

        $this->projectFile = $basePath . '/fixtures/project2.json';
        $this->templateDir = dirname($basePath) . '/src/templates/swagger.io';
        $this->outputDir   = $basePath . '/tmp/swagger.io';

        $project = new Project(json_decode(file_get_contents($this->projectFile), true));

        (new Generator())->project($project)
                         ->template($this->templateDir)
                         ->output($this->outputDir)
                         ->generate()
        ;
    }

    public function tearDown()
    {
        if (is_dir($this->outputDir)) {
            shell_exec("rm -rf {$this->outputDir}");
        }
    }

    /**
     * @testdox swagger-codegen-cli generate - Generate code with chosen lang
     */
    public function testSwaggerGenerator(): void
    {
        $this->markTestSkipped('Skipped until Swagger problem is solved');
        $swagger = new Swagger();

        $path = dirname(__DIR__) . '/tmp/swagger.io/api-doc';

        if (!file_exists($path) && !mkdir($path, 0777, true) && !is_dir($path)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $path));
        }

        $swagger->generate('--input-spec /local/tests/tmp/swagger.io/swagger.yml --lang html2 --output /local/tests/tmp/swagger.io/api-doc');

        $this->assertFileExists("{$path}/index.html");
        $this->assertEquals(getmyuid(), fileowner("{$path}/index.html"));
    }

    /**
     * @testdox swagger-codegen-cli config-help - Config help for chosen lang
     */
    public function testConfigHelp(): void
    {
        $swagger = new Swagger();

        $result = $swagger->configHelp('php');

        $this->assertContains('CONFIG OPTIONS', $result);
    }

    /**
     * @testdox swagger-codegen-cli help - Display help information
     */
    public function testHelp(): void
    {
        $swagger = new Swagger();
        $result  = $swagger->help();

        $this->assertContains('usage: swagger-codegen-cli', $result);
    }

    /**
     * @testdox swagger-codegen-cli help <command> - Display help information for a command
     */
    public function testHelpWithParam(): void
    {
        $swagger = new Swagger();
        $result  = $swagger->help('generate');

        $this->assertContains('swagger-codegen-cli generate', $result);
    }

    /**
     * @testdox swagger-codegen-cli langs - Shows available langs
     */
    public function testLanguages(): void
    {
        $swagger = new Swagger();

        $result = $swagger->languages();

        $this->assertContains('html2', $result);
    }

    /**
     * @testdox swagger-codegen-cli meta - MetaGenerator
     */
    public function testMeta(): void
    {
        $swagger = new Swagger();

        $swagger->meta('-o /local/tests/tmp/generated');

        $this->assertFileExists('tests/tmp/generated/pom.xml');
        shell_exec('rm -rf tests/tmp/generated');
    }

    /**
     * @testdox swagger-codegen-cli version - Show version information
     */
    public function testVersion(): void
    {
        $swagger = new Swagger();

        $result = $swagger->version();

        $this->assertRegExp('~^\d+\.\d+\.\d+~', $result);
    }
}
