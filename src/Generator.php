<?php

namespace GreenCape\CodeGen;

class Generator
{
    private $project;
    private $templatePath;
    private $outputPath;

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
        $context = [
            'project' => $this->project->properties,
            'entities' => $this->project->entities,
        ];

        /** @var \SplFileInfo $info */
        foreach ($this->getRecursiveDirectoryIterator($this->templatePath) as $info) {
            $source = $info->getPathname();
            $destination = $this->getDestinationPath($source);

            if ($info->isDir()) {
                mkdir($destination, 0777, true);
                continue;
            }

            file_put_contents($destination, (new Template($source))->render($context));
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
     * @return string
     */
    private function getDestinationPath($source): string
    {
        $replace = [
            $this->templatePath => $this->outputPath,
            '{{project}}' => $this->project->name,
        ];
        $destination = str_replace(array_keys($replace), array_values($replace), $source);
        return $destination;
    }
}
