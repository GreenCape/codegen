<?php

namespace GreenCape\CodeGen\Tests\Templates;

use Exception;
use GreenCape\CodeGen\Definition\Project;
use GreenCape\CodeGen\Generator;
use Overtrue\PHPLint\Linter;
use PHPUnit\Framework\TestCase;

class JoomlaXTest extends TestCase
{
    private $projectFile;

    private $templateDir;

    private $outputDir;

    public function setUp()
    {
        $basePath = dirname(__DIR__);

        $this->projectFile = $basePath . '/fixtures/all_relations_project.json';
        $this->templateDir = dirname($basePath) . '/src/templates/joomlax';
        $this->outputDir = $basePath . '/tmp/joomlax';
    }

    public function tearDown()
    {
        if (is_dir($this->outputDir)) {
            shell_exec("rm -rf {$this->outputDir}");
        }
    }

    /**
     * @testdox Joomla! X template compiles
     * @throws Exception
     */
    public function testJoomlaX(): void
    {
        $project = new Project(json_decode(file_get_contents($this->projectFile), true));

        (new Generator())
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
    public function testLint(): void
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
