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
        $template = new \GreenCape\CodeGen\Template($this->templateDir . '/template1/{{project}}/README.md');

        $this->assertEquals('application', $template->getScope());
    }

    /**
     * @testdox An exception (1001) is thrown, if the template directive is missing from a template
     */
    public function testExceptionOnMissingDeclaration()
    {
        $this->expectExceptionCode(1001);

        new \GreenCape\CodeGen\Template($this->templateDir . '/template0/missing-declaration.tpl');
    }

    /**
     * @testdox An exception (1002) is thrown, if the template directive does not have a scope attribute
     */
    public function testExceptionOnMissingScope()
    {
        $this->expectExceptionCode(1002);

        new \GreenCape\CodeGen\Template($this->templateDir . '/template0/missing-scope.tpl');
    }

    /**
     * @testdox Template condition is empty, if not declared
     */
    public function testGetEmptyCondition()
    {
        $template = new \GreenCape\CodeGen\Template($this->templateDir . '/template1/{{project}}/README.md');

        $this->assertEquals('', $template->getCondition());
    }

    /**
     * @testdox Template conditions are recognized
     */
    public function testGetCondition()
    {
        $template = new \GreenCape\CodeGen\Template($this->templateDir . '/template0/with-condition.tpl');

        $this->assertEquals('hasKey()', $template->getCondition());
    }
}
