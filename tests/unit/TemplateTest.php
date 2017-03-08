<?php

class TemplateTest extends \PHPUnit\Framework\TestCase
{
    private $templateDir;

    public function setUp()
    {
        $basePath = dirname(__DIR__);

        $this->templateDir = $basePath . '/fixtures';
    }

    /**
     * @testdox The template scope is recognized
     */
    public function testGetScope()
    {
        $template = new \GreenCape\CodeGen\Template($this->templateDir . '/template1/$/README.md');

        $this->assertEquals('application', $template->getScope());
    }

    /**
     * @testdox An exception (1000) is thrown, if the template has parse errors
     */
    public function testExceptionOnBadSyntax()
    {
        $this->expectExceptionCode(1000);

        $template = new \GreenCape\CodeGen\Template($this->templateDir . '/template0/bad-syntax.tpl');
        $template->render(['foo' => 'bar']);
    }

    /**
     * @testdox An exception (1002) is thrown, if the template directive does not have a scope attribute
     */
    public function testExceptionOnMissingScope()
    {
        $this->expectExceptionCode(1002);

        new \GreenCape\CodeGen\Template($this->templateDir . '/template0/missing-scope.tpl');
    }
}
