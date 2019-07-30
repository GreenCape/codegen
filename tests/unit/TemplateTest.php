<?php

namespace GreenCape\CodeGen\Tests\Unit;

use RuntimeException;
use GreenCape\CodeGen\Inflector;
use GreenCape\CodeGen\Template;
use PHPUnit\Framework\TestCase;

class TemplateTest extends TestCase
{
    private $templateDir;

    /**
     * @var Inflector
     */
    private $inflector;

    public function setUp()
    {
        $basePath = dirname(__DIR__);

        $this->templateDir = $basePath . '/fixtures';
        $this->inflector = new Inflector();
    }

    /**
     * @testdox The template scope is recognized
     * @throws RuntimeException
     */
    public function testGetScope(): void
    {
        $template = new Template($this->templateDir . '/template1/$/README.md', $this->inflector);

        $this->assertEquals('application', $template->getScope());
    }

    /**
     * @testdox An exception (1000) is thrown, if the template has parse errors
     * @throws RuntimeException
     */
    public function testExceptionOnBadSyntax(): void
    {
        $this->expectExceptionCode(1000);

        $template = new Template($this->templateDir . '/template0/bad-syntax.tpl', $this->inflector);
        $template->render(['foo' => 'bar']);
    }

    /**
     * @testdox An exception (1002) is thrown, if the template directive does not have a scope attribute
     * @throws RuntimeException
     */
    public function testExceptionOnMissingScope(): void
    {
        $this->expectExceptionCode(1002);

        new Template($this->templateDir . '/template0/missing-scope.tpl', $this->inflector);
    }
}
