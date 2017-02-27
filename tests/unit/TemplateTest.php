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
           # `rm -rf $this->outputDir`;
        }
    }

    public function testFixturesExist()
    {
        $this->assertFileExists($this->projectFile);
        $this->assertDirectoryExists($this->templateDir);
    }

    public function testGeneratesOutputDirectory()
    {
        $generator = new \GreenCape\CodeGen\Generator();
        $generator
            ->output($this->outputDir);

        $this->assertDirectoryExists($this->outputDir);
    }

    public function testProjectInfo()
    {
        $configuration = json_decode(file_get_contents($this->projectFile));

        (new \GreenCape\CodeGen\Generator())
            ->project($configuration)
            ->template($this->templateDir)
            ->output($this->outputDir)
            ->generate();

        $outputDir = $this->outputDir . '/' . $configuration->name;

        $this->assertDirectoryExists($outputDir);

        $readme = file_get_contents($outputDir . '/README.md');

        $this->assertContains('# ' . $configuration->title, $readme);
    }
}
