<?php

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
        $this->outputDir = $basePath . '/tmp';
    }

    public function tearDown()
    {
        if (is_dir($this->outputDir))
        {
            `rm -rf $this->outputDir`;
        }
    }

    /**
     * @testdox Output directory is created automatically
     */
    public function testGeneratesOutputDirectory()
    {
        $generator = new \GreenCape\CodeGen\Generator();
        $generator
            ->output($this->outputDir);

        $this->assertDirectoryExists($this->outputDir);
    }

    /**
     * @testdox Output directory is cleared, if it already exists
     */
    public function testClearsOutputDirectory()
    {
        $testDir = $this->outputDir . '/test_project_1';
        $testFile = $testDir . '/should_not_be.here';

        mkdir($testDir, 0777, true);
        touch($testFile);

        $generator = new \GreenCape\CodeGen\Generator();
        $generator
            ->output($this->outputDir);

        $this->assertFileNotExists($testFile);
    }

    /**
     * @testdox Placeholder {{project}} in the path is replaced with the project name
     */
    public function testProjectNameIsReplaced()
    {
        $project = new \GreenCape\CodeGen\Project($this->projectFile);

        (new \GreenCape\CodeGen\Generator())
            ->project($project)
            ->template($this->templateDir)
            ->output($this->outputDir)
            ->generate();

        $outputDir = $this->outputDir . '/' . $project->name;

        $this->assertDirectoryExists($outputDir);
    }

    /**
     * @testdox Configuration values from the project JSON file are replaced
     */
    public function testConfigValuesAreReplaced()
    {
        $project = new \GreenCape\CodeGen\Project($this->projectFile);

        (new \GreenCape\CodeGen\Generator())
            ->project($project)
            ->template($this->templateDir)
            ->output($this->outputDir)
            ->generate();

        $outputDir = $this->outputDir . '/' . $project->name;

        $readme = file_get_contents($outputDir . '/README.md');

        $this->assertContains('# ' . $project->title, $readme);
    }


    /**
     * @testdox Template directive is removed
     */
    public function testTemplateDirectiveIsRemoved()
    {
        $project = new \GreenCape\CodeGen\Project($this->projectFile);

        (new \GreenCape\CodeGen\Generator())
            ->project($project)
            ->template($this->templateDir)
            ->output($this->outputDir)
            ->generate();

        $outputDir = $this->outputDir . '/' . $project->name;

        $readme = file_get_contents($outputDir . '/README.md');

        $this->assertNotContains('<?template', $readme);
    }
}