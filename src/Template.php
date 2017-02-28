<?php

namespace GreenCape\CodeGen;

class Template
{
    private $template;

    private $scope;

    private $condition = '';

    /** @var  Inflector */
    private $inflector;

    public function __construct($templateFile)
    {
        $template = file_get_contents($templateFile);

        if (!preg_match('~^\s*\<\?template\s+(.*?)\s*\?\>\s*~sim', $template, $matches)) {
            throw new \Exception('Template has no template declaration', 1001);
        }

        $this->template = str_replace($matches[0], '', $template);

        $attributes = $matches[1];
        if (!preg_match('~\s*scope=(["\'])(.*?)\1\s*~sim', $attributes, $scope)) {
            throw new \Exception('Template declaration has no scope attribute', 1002);
        }

        $this->scope = $scope[2];
        $attributes = str_replace($scope[0], '', $attributes);

        if (preg_match('~\s*condition=(["\'])(.*?)\1\s*~sim', $attributes, $condition)) {
            $this->condition = $condition[2];
        }

        $this->inflector = new Inflector();
    }

    public function getScope(): string
    {
        return $this->scope;
    }

    public function getCondition(): string
    {
        return $this->condition;
    }

    public function render(array $context): string
    {
        $filters = [
            'singular', 'plural',
            'title', 'variable', 'class', 'table', 'dash',
        ];
        $twig = new \Twig_Environment(new \Twig_Loader_Array(['template' => $this->template]));
        foreach ($filters as $filter) {
            $this->addFilter($twig, $filter);
        }

        return $twig->render('template', $context);
    }

    private function addFilter(\Twig_Environment $twig, $filter)
    {
        $twig->addFilter(new \Twig_Filter($filter, function($string) use ($filter) {
            return $this->inflector->apply($filter, $string);
        }));
    }
}
