<?php

namespace GreenCape\CodeGen\Tests\Unit;

use Exception;
use GreenCape\CodeGen\Definition\Project;
use GreenCape\CodeGen\Generator;
use PHPUnit\Framework\TestCase;

class GeneratorTest extends TestCase
{
    private $projectFile;

    private $templateDir;

    private $outputDir;

    /**
     *
     */
    public function setUp()
    {
        $basePath = dirname(__DIR__);

        $this->projectFile = $basePath . '/fixtures/project1.json';
        $this->templateDir = $basePath . '/fixtures/template1';
        $this->outputDir   = $basePath . '/tmp';
    }

    public function tearDown()
    {
        if (is_dir($this->outputDir)) {
            shell_exec("rm -rf {$this->outputDir}");
        }
    }

    /**
     * @testdox Output directory is created automatically
     */
    public function testGeneratesOutputDirectory(): void
    {
        $generator = new Generator();
        $generator->output($this->outputDir);

        $this->assertDirectoryExists($this->outputDir);
    }

    /**
     * @testdox Output directory is cleared, if it already exists
     */
    public function testClearsOutputDirectory(): void
    {
        $testDir  = $this->outputDir . '/test_project_1';
        $testFile = $testDir . '/should_not_be.here';

        mkdir($testDir, 0777, true);
        touch($testFile);

        $generator = new Generator();
        $generator->output($this->outputDir);

        $this->assertFileNotExists($testFile);
    }

    /**
     * @testdox Placeholder $ in the path is replaced with the project name
     * @throws Exception
     */
    public function testProjectNameIsReplaced(): void
    {
        $project   = new Project(json_decode(file_get_contents($this->projectFile), true));
        $outputDir = $this->generate($project);

        $this->assertDirectoryExists($outputDir);
    }

    /**
     * @param Project $project
     *
     * @return string
     * @throws Exception
     */
    private function generate(Project $project): string
    {
        (new Generator())->project($project)
                                            ->template($this->templateDir)
                                            ->output($this->outputDir)
                                            ->generate()
        ;

        return $this->outputDir . '/' . $project->name;
    }

    /**
     * @testdox Configuration values from the project JSON file are replaced
     * @throws Exception
     */
    public function testConfigValuesAreReplaced(): void
    {
        $project   = new Project(json_decode(file_get_contents($this->projectFile), true));
        $outputDir = $this->generate($project);

        $this->assertContains('# ' . $project->title, file_get_contents($outputDir . '/README.md'));
    }

    /**
     * @testdox Template directive is removed
     * @throws Exception
     */
    public function testTemplateDirectiveIsRemoved(): void
    {
        $project   = new Project(json_decode(file_get_contents($this->projectFile), true));
        $outputDir = $this->generate($project);

        $this->assertNotContains('<?template', file_get_contents($outputDir . '/README.md'));
    }
}
