<?php

namespace GreenCape\CodeGen\Tests\Templates;

use Exception;
use GreenCape\CodeGen\Definition\Project;
use GreenCape\CodeGen\Generator;
use Overtrue\PHPLint\Linter;
use PHPUnit\Framework\TestCase;

class Joomla3Test extends TestCase
{
    private $projectFile;

    private $templateDir;

    private $outputDir;

    public function setUp()
    {
        $basePath = dirname(__DIR__);

        $this->projectFile = $basePath . '/fixtures/all_relations_project.json';
        $this->templateDir = dirname($basePath) . '/src/templates/joomla3';
        $this->outputDir = $basePath . '/tmp/joomla3';
    }

    public function tearDown()
    {
        if (is_dir($this->outputDir)) {
            shell_exec("rm -rf {$this->outputDir}");
        }
    }

    /**
     * @testdox Joomla 3 template compiles
     * @throws Exception
     */
    public function testJoomla3(): void
    {
        $project = new Project(json_decode(file_get_contents($this->projectFile), true));

        (new Generator())
            ->project($project)
            ->template($this->templateDir)
            ->output($this->outputDir)
            ->generate();

        $this->assertFileExists($this->outputDir . '/src/administrator/components/com_all_relations_project/all_relations_project.php');
        $this->assertFileExists($this->outputDir . '/src/administrator/components/com_all_relations_project/all_relations_project.php');
        $this->assertFileExists($this->outputDir . '/src/administrator/components/com_all_relations_project/all_relations_project.xml');
        $this->assertFileExists($this->outputDir . '/src/components/com_all_relations_project/all_relations_project.php');

        $this->assertFileExists($this->outputDir . '/composer.json');
        $this->assertRegExp("~\"name\"\s*:\s*\"nibralab/all_relations_project\"~", file_get_contents($this->outputDir . '/composer.json'));
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
