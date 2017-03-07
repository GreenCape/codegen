<?php

namespace GreenCape\CodeGen;

class Generator
{
    private $project;
    private $templatePath;
    private $outputPath;

    /** @var  Inflector */
    private $inflector;

    public function project(Project $project): Generator
    {
        $this->project = $project;

        return $this;
    }

    public function template(string $path): Generator
    {
        $this->templatePath = $path;

        return $this;
    }

    public function output(string $path): Generator
    {
        $this->outputPath = $path;

        if (is_dir($path)) {
            `rm -rf $path`;
        }
        mkdir($path, 0777, true);

        return $this;
    }

    public function generate()
    {
        $this->inflector = new Inflector();

        /** @var \SplFileInfo $info */
        foreach ($this->getRecursiveDirectoryIterator($this->templatePath) as $info) {
            $source = $info->getPathname();
            $template = new Template($source);
            $scope = $template->getScope();

            if ($scope == 'entity' || strpos($source, '#') !== false)
            {
                foreach ($this->project->entities as $entity) {
                    $this->render($template, $source, $entity);
                }
            }
            else
            {
                $this->render($template, $source);
            }
        }
    }

    /**
     * @param $path
     * @return \RecursiveIteratorIterator
     */
    private function getRecursiveDirectoryIterator($path): \RecursiveIteratorIterator
    {
        $directory = new \RecursiveDirectoryIterator($path, \FilesystemIterator::FOLLOW_SYMLINKS);
        $filter = new \RecursiveCallbackFilterIterator($directory, function (\SplFileInfo $current) {
            if ($current->getFilename() == '.' || $current->getFilename() == '..') {
                return false;
            }
            return true;
        });
        return new \RecursiveIteratorIterator($filter, \RecursiveIteratorIterator::SELF_FIRST);
    }

    /**
     * @param $source
     * @param array $entity
     * @return string
     */
    private function getDestinationPath($source, $entity = null): string
    {
        $name = is_null($entity) ? 'entity' : $entity['name'];
        $replace = [
            $this->templatePath => $this->outputPath,
            '$' => $this->inflector->fileName($this->project->name),
            '#s' => $this->inflector->fileName($this->inflector->plural($name)),
            '#' => $this->inflector->fileName($name),
        ];
        $destination = str_replace(array_keys($replace), array_values($replace), $source);
        return $destination;
    }

    /**
     * @param Template $template
     * @param string   $source
     * @param array    $entity
     */
    private function render($template, $source, $entity = null)
    {
        $destination = $this->getDestinationPath($source, $entity);

        if (is_dir($source)) {
            mkdir($destination, 0777, true);
            return;
        }

        $context = [
            'project' => $this->project->properties,
            'entity' => $entity
        ];

        $content = $template->render($context);

        if (trim($content) > '') {
            file_put_contents($destination, $content);
        }

        return;
    }
}
