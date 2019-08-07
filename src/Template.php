<?php

namespace GreenCape\CodeGen;

use Exception;
use RuntimeException;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\ArrayLoader;
use Twig\TwigFilter;

class Template
{
    private $template;

    private $scope = '';

    private $templateFile;

    /** @var  Inflector */
    private $inflector;

    private $isVerbatim;

    /**
     * Template constructor.
     *
     * @param string    $templateFile
     * @param Inflector $inflector
     *
     * @throws RuntimeException
     */
    public function __construct(string $templateFile, Inflector $inflector)
    {
        $this->templateFile = $templateFile;
        $template           = file_get_contents($this->templateFile);

        $this->isVerbatim = true;
        if (preg_match('~^\s*\<\?template\s+(.*?)\s*\?\>\s*~sim', $template, $matches)) {
            $this->isVerbatim = false;

            $template   = str_replace($matches[0], '', $template);
            $attributes = $matches[1];

            if (!preg_match('~\s*scope\s*=\s*(["\'])(.*?)\1\s*~sim', $attributes, $scope)) {
                throw new RuntimeException('Template declaration has no scope attribute in ' . $this->templateFile, 1002);
            }

            $this->scope = $scope[2];
        }

        $this->template  = $template;
        $this->inflector = $inflector;
    }

    /**
     * @return string
     */
    public function getScope(): string
    {
        return $this->scope;
    }

    /**
     * @param array $context
     *
     * @return string
     */
    public function render(array $context): string
    {
        static $filters = [
            'singular',
            'plural',
            'title',
            'variable',
            'class',
            'table',
            'dash',
            'file',
            'constant',
            'namespace',
        ];

        if ($this->isVerbatim) {
            return $this->template;
        }

        $twig = new Environment(new ArrayLoader([$this->templateFile => $this->template]), [
            'debug' => true,
            // ...
        ]);
        $twig->addExtension(new DebugExtension());

        array_map(function ($filter) use ($twig) {
            $this->addFilter($twig, $filter);
        }, $filters);

        try {
            $template = $twig->render($this->templateFile, $context);
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage(), 1000);
        }

        return preg_replace(["/[\t ]+\n/", "/\n\n\n+/"], ["\n", "\n\n"], $template);
    }

    /**
     * @param Environment $twig
     * @param string      $filter
     */
    private function addFilter(Environment $twig, string $filter): void
    {
        $twig->addFilter(new TwigFilter($filter, function ($string) use ($filter) {
            return $this->inflector->apply($filter, $string);
        }));
    }
}
