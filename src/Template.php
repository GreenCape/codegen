<?php

namespace GreenCape\CodeGen;

class Template
{
    private $template;

    private $scope;

    private $condition = '';

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
        $twig = new \Twig_Environment(new \Twig_Loader_Array(['template' => $this->template]));

        return $twig->render('template', $context);
    }
}
