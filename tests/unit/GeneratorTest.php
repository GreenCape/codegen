<?php

namespace GreenCape\CodeGen\Tests\Unit;

use GreenCape\CodeGen\Definition\Project;

class GeneratorTest extends \PHPUnit\Framework\TestCase
{
    private $projectFile;

    private $templateDir;

    private $outputDir;

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
            `rm -rf $this->outputDir`;
        }
    }

    /**
     * @testdox Output directory is created automatically
     */
    public function testGeneratesOutputDirectory()
    {
        $generator = new \GreenCape\CodeGen\Generator();
        $generator->output($this->outputDir);

        $this->assertDirectoryExists($this->outputDir);
    }

    /**
     * @testdox Output directory is cleared, if it already exists
     */
    public function testClearsOutputDirectory()
    {
        $testDir  = $this->outputDir . '/test_project_1';
        $testFile = $testDir . '/should_not_be.here';

        mkdir($testDir, 0777, true);
        touch($testFile);

        $generator = new \GreenCape\CodeGen\Generator();
        $generator->output($this->outputDir);

        $this->assertFileNotExists($testFile);
    }

    /**
     * @testdox Placeholder $ in the path is replaced with the project name
     */
    public function testProjectNameIsReplaced()
    {
        $project   = new Project(json_decode(file_get_contents($this->projectFile), true));
        $outputDir = $this->generate($project);

        $this->assertDirectoryExists($outputDir);
    }

    /**
     * @param $project
     *
     * @return string
     */
    private function generate($project): string
    {
        (new \GreenCape\CodeGen\Generator())->project($project)
                                            ->template($this->templateDir)
                                            ->output($this->outputDir)
                                            ->generate()
        ;

        return $this->outputDir . '/' . $project->name;
    }

    /**
     * @testdox Configuration values from the project JSON file are replaced
     */
    public function testConfigValuesAreReplaced()
    {
        $project   = new Project(json_decode(file_get_contents($this->projectFile), true));
        $outputDir = $this->generate($project);

        $this->assertContains('# ' . $project->title, file_get_contents($outputDir . '/README.md'));
    }

    /**
     * @testdox Template directive is removed
     */
    public function testTemplateDirectiveIsRemoved()
    {
        $project   = new Project(json_decode(file_get_contents($this->projectFile), true));
        $outputDir = $this->generate($project);

        $this->assertNotContains('<?template', file_get_contents($outputDir . '/README.md'));
    }
}
