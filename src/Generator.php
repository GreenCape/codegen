<?php

namespace GreenCape\CodeGen;

use Exception;
use FilesystemIterator;
use GreenCape\CodeGen\Definition\Entity;
use GreenCape\CodeGen\Definition\Project;
use RecursiveCallbackFilterIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;
use SplFileInfo;

class Generator
{
    /** @var Project */
    private $project;

    /** @var string */
    private $templatePath;

    /** @var string */
    private $outputPath;

    /** @var string */
    private $filenameFilter = 'file';

    /** @var  Inflector */
    private $inflector;

    /**
     * @param Project $project
     *
     * @return Generator
     */
    public function project(Project $project): Generator
    {
        $this->project = $project;

        return $this;
    }

    /**
     * @param string $filter
     *
     * @return Generator
     */
    public function filenameFilter(string $filter): Generator
    {
        $this->filenameFilter = $filter;

        return $this;
    }

    /**
     * @param string $path
     *
     * @return Generator
     */
    public function template(string $path): Generator
    {
        $this->templatePath = $path;

        return $this;
    }

    /**
     * @param string $path
     *
     * @return Generator
     */
    public function output(string $path): Generator
    {
        $this->outputPath = $path;

        if (is_dir($path)) {
            shell_exec("rm -rf $path");
        }
        if (!mkdir($path, 0777, true) && !is_dir($path)) {
            // @codeCoverageIgnoreStart
            throw new RuntimeException(sprintf('Directory "%s" was not created', $path));
            // @codeCoverageIgnoreEnd
        }

        return $this;
    }

    /**
     * @throws Exception
     */
    public function generate(): void
    {
        $this->inflector = new Inflector();

        foreach ($this->getRecursiveDirectoryIterator($this->templatePath) as $info) {
            $source   = $info->getPathname();
            $template = new Template($source, $this->inflector);
            $scope    = $template->getScope();

            if ($scope === 'entity' || strpos($source, '#') !== false) {
                foreach ($this->project->entities as $entity) {
                    $this->render($template, $source, $entity);
                }
            } else {
                $this->render($template, $source);
            }
        }
    }

    /**
     * @param string $path
     *
     * @return RecursiveIteratorIterator | SplFileInfo[]
     */
    private function getRecursiveDirectoryIterator(string $path): RecursiveIteratorIterator
    {
        $directory = new RecursiveDirectoryIterator($path, FilesystemIterator::FOLLOW_SYMLINKS);
        $filter    = new RecursiveCallbackFilterIterator($directory, static function (SplFileInfo $current) {
            return !in_array($current->getFilename(), [
                '.',
                '..',
            ]);
        });

        return new RecursiveIteratorIterator($filter, RecursiveIteratorIterator::SELF_FIRST);
    }

    /**
     * @param Template $template
     * @param string   $source
     * @param Entity   $entity
     *
     * @throws Exception
     */
    private function render(Template $template, $source, Entity $entity = null): void
    {
        $destination = $this->getDestinationPath($source, $entity);

        if (is_dir($source)) {
            if (!mkdir($destination, 0777, true) && !is_dir($destination)) {
                // @codeCoverageIgnoreStart
                throw new RuntimeException(sprintf('Directory "%s" was not created', $destination));
                // @codeCoverageIgnoreEnd
            }

            return;
        }

        $context = [
            'project' => $this->project,
            'entity'  => $entity,
        ];

        $content = $template->render($context);

        if (trim($content) > '') {
            file_put_contents($destination, $content);
        }
    }

    /**
     * @param string $source
     * @param Entity $entity
     *
     * @return string
     */
    private function getDestinationPath(string $source, Entity $entity = null): string
    {
        $name        = $entity === null ? 'entity' : $entity->name;
        $replace     = [
            $this->templatePath => $this->outputPath,
            '$'                 => $this->inflector->apply($this->filenameFilter, $this->project->name),
            '##'                => $this->inflector->apply($this->filenameFilter, $this->inflector->plural($name)),
            '#'                 => $this->inflector->apply($this->filenameFilter, $name),
        ];
        $destination = str_replace(array_keys($replace), array_values($replace), $source);

        return $destination;
    }
}
