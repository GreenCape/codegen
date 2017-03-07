<?php

class Joomla25Test extends \PHPUnit\Framework\TestCase
{
    private $projectFile;

    private $templateDir;

    private $outputDir;

    public function setUp()
    {
        $basePath = dirname(__DIR__);

        $this->projectFile = $basePath . '/fixtures/project1.json';
        $this->templateDir = dirname($basePath) . '/src/templates/joomla25';
        $this->outputDir = $basePath . '/tmp';
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
        $project = new \GreenCape\CodeGen\Project($this->projectFile);
        $project->addEntity(json_decode(file_get_contents(dirname($this->projectFile) . '/entities/article.json'), true));

        (new \GreenCape\CodeGen\Generator())
            ->project($project)
            ->template($this->templateDir)
            ->output($this->outputDir)
            ->generate();
    }
}
