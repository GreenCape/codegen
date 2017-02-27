<?php

class TemplateTest extends \PHPUnit\Framework\TestCase
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
     * @testdox The fixtures required by the tests are available
     */
    public function testFixturesExist()
    {
        $this->assertFileExists($this->projectFile);
        $this->assertDirectoryExists($this->templateDir);
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
        $configuration = json_decode(file_get_contents($this->projectFile));

        (new \GreenCape\CodeGen\Generator())
            ->project($configuration)
            ->template($this->templateDir)
            ->output($this->outputDir)
            ->generate();

        $outputDir = $this->outputDir . '/' . $configuration->name;

        $this->assertDirectoryExists($outputDir);
    }

    /**
     * @testdox Configuration values from the project JSON file are replaced
     */
    public function testConfigValuesAreReplaced()
    {
        $configuration = json_decode(file_get_contents($this->projectFile));

        (new \GreenCape\CodeGen\Generator())
            ->project($configuration)
            ->template($this->templateDir)
            ->output($this->outputDir)
            ->generate();

        $outputDir = $this->outputDir . '/' . $configuration->name;

        $readme = file_get_contents($outputDir . '/README.md');

        $this->assertContains('# ' . $configuration->title, $readme);
    }
}
