<?php

namespace GreenCape\CodeGen\Tests\Templates;

use GreenCape\CodeGen\Definition\Project;
use Overtrue\PHPLint\Linter;

class JoomlaXTest extends \PHPUnit\Framework\TestCase
{
    private $projectFile;

    private $templateDir;

    private $outputDir;

    public function setUp()
    {
        $basePath = dirname(__DIR__);

        $this->projectFile = $basePath . '/fixtures/project2.json';
        $this->templateDir = dirname($basePath) . '/src/templates/joomlax';
        $this->outputDir = $basePath . '/tmp/joomlax';
    }

    public function tearDown()
    {
        if (is_dir($this->outputDir)) {
            #`rm -rf $this->outputDir`;
        }
    }

    /**
     * @testdox Joomla! X template compiles
     */
    public function testJoomlaX()
    {
        $project = new Project(json_decode(file_get_contents($this->projectFile), true));

        (new \GreenCape\CodeGen\Generator())
            ->project($project)
            ->filenameFilter('class')
            ->template($this->templateDir)
            ->output($this->outputDir)
            ->generate();

        $this->assertFileExists($this->outputDir . '/entities/Article.xml');
    }

    /**
     * @testdox The generated PHP files do not contain syntax errors
     */
    public function testLint()
    {
        $linter = new Linter($this->outputDir, ['build'], ['php']);

        $errors = [];

        // Compress errors for output
        foreach ($linter->lint() as $error) {
            $errors[] = $error['error'] . ' in ' . str_replace($this->outputDir . '/', '', $error['file']);
        }

        $this->assertEquals('', implode("\n", $errors));
    }
}
