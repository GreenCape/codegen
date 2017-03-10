<?php

use GreenCape\CodeGen\Definition\Entity;
use GreenCape\CodeGen\Definition\Project;
use GreenCape\CodeGen\Definition\Registry;
use Overtrue\PHPLint\Linter;

class Joomla25Test extends \PHPUnit\Framework\TestCase
{
    private $projectFile;

    private $templateDir;

    private $outputDir;

    public function setUp()
    {
        $basePath = dirname(__DIR__);

        $this->projectFile = $basePath . '/fixtures/project2.json';
        $this->templateDir = dirname($basePath) . '/src/templates/joomla25';
        $this->outputDir = $basePath . '/tmp/joomla25';
    }

    public function tearDown()
    {
        if (is_dir($this->outputDir)) {
            #`rm -rf $this->outputDir`;
        }
    }

    /**
     * @testdox Joomla 2.5 template compiles
     */
    public function testJoomla25()
    {
        $project = new Project(json_decode(file_get_contents($this->projectFile), true));

        (new \GreenCape\CodeGen\Generator())
            ->project($project)
            ->template($this->templateDir)
            ->output($this->outputDir)
            ->generate();

        $this->assertFileExists($this->outputDir . '/source/administrator/all_relations_project.php');
        $this->assertFileExists($this->outputDir . '/source/administrator/controllers/article.php');
        $this->assertFileExists($this->outputDir . '/source/administrator/controllers/articles.php');
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
