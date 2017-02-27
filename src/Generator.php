<?php

namespace GreenCape\CodeGen;

class Generator
{
    private $configuration;
    private $templatePath;
    private $outputPath;

    public function project($configuration): Generator
    {
        $this->configuration = $configuration;

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

        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        return $this;
    }

    public function generate()
    {
        /** @var \SplFileInfo $info */
        foreach ($this->getRecursiveDirectoryIterator($this->templatePath) as $info) {
            $source = $info->getPathname();
            $destination = $this->getDestinationPath($source);

            if ($info->isDir()) {
                mkdir($destination, 0777, true);
                continue;
            }

            $twig = new \Twig_Environment(new \Twig_Loader_Filesystem([$this->templatePath]));
            $context = get_object_vars($this->configuration);
            file_put_contents($destination, $twig->render(str_replace($this->templatePath, '', $source), $context));
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
            '{{project}}' => $this->configuration->name,
        ];
        $destination = str_replace(array_keys($replace), array_values($replace), $source);
        return $destination;
    }
}
