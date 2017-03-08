<?php

namespace GreenCape\CodeGen;

class Template
{
    private $template;

    private $scope = '';

    private $templateFile;

    /** @var  Inflector */
    private $inflector;

    private $isVerbatim = false;

    public function __construct($templateFile)
    {
        $this->templateFile = $templateFile;
        $template           = file_get_contents($this->templateFile);

        $this->isVerbatim = true;
        if (preg_match('~^\s*\<\?template\s+(.*?)\s*\?\>\s*~sim', $template, $matches)) {
            $this->isVerbatim = false;

            $template   = str_replace($matches[0], '', $template);
            $attributes = $matches[1];

            if (!preg_match('~\s*scope\s*=\s*(["\'])(.*?)\1\s*~sim', $attributes, $scope)) {
                throw new \Exception('Template declaration has no scope attribute in ' . $this->templateFile, 1002);
            }

            $this->scope = $scope[2];
        }

        $this->template  = $template;
        $this->inflector = new Inflector();
    }

    public function getScope(): string
    {
        return $this->scope;
    }

    public function render(array $context): string
    {
        if ($this->isVerbatim) {
            return $this->template;
        }

        $filters = ['singular', 'plural', 'title', 'variable', 'class', 'table', 'dash', 'file', 'constant',];
        $twig    = new \Twig_Environment(new \Twig_Loader_Array([$this->templateFile => $this->template]));
        foreach ($filters as $filter) {
            $this->addFilter($twig, $filter);
        }

        try {
            $template = $twig->render($this->templateFile, $context);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 1000);
        }

        return $template;
    }

    private function addFilter(\Twig_Environment $twig, $filter)
    {
        $twig->addFilter(new \Twig_Filter($filter, function ($string) use ($filter) {
            return $this->inflector->apply($filter, $string);
        }));
    }
}
