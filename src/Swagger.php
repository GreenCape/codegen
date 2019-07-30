<?php

namespace GreenCape\CodeGen;

/**
 * Class Swagger
 *
 * Adapter to the docker image `swaggerapi/swagger-codegen-cli`
 *
 * Example:
 *      Swagger::generate()
 * maps to
 *      docker run --rm -v ${PWD}:/local swaggerapi/swagger-codegen-cli generate
 *
 * @package GreenCape\CodeGen
 */
class Swagger
{
    /**
     * Get config help for chosen lang
     *
     * @param string $language The language to get config help for
     *
     * @return string
     */
    public function configHelp(string $language): string
    {
        return $this->execute('config-help', "-l $language");
    }

    /**
     * @param string $command
     * @param string $paramString
     *
     * @return string
     */
    private function execute(string $command, string $paramString = ''): ?string
    {
        $dir     = getcwd();
        $userId  = getmyuid() . ':' . getmygid();
        $volume  = "--volume {$dir}:/local";
        $image   = 'swaggerapi/swagger-codegen-cli';
        $version = '2.4.7';

        $dockerCommand = "docker run --rm {$volume} --user {$userId} --name temp_swagger {$image}:{$version} {$command} {$paramString} 2>&1";

        return shell_exec($dockerCommand);
    }

    /**
     * Generate code with chosen lang
     *
     * @param string $paramString Complete parameter string
     *
     * @return string
     * @todo  Make this more dev friendly
     */
    public function generate(string $paramString): ?string
    {
        return $this->execute('generate', $paramString);
    }

    /**
     * Display help information
     *
     * @param string $command
     *
     * @return string
     */
    public function help(string $command = ''): string
    {
        return $this->execute('help', $command);
    }

    /**
     * Show available languages
     */
    public function languages()
    {
        $languages = preg_replace('~^Available languages: \[(.*)\]$~', '\\1', $this->execute('langs'));
        $languages = preg_split('~,\s*~', $languages);

        return $languages;
    }

    /**
     * MetaGenerator.
     *
     * Generator for creating a new template set and configuration for Codegen.
     * The output will be based on the language you specify, and includes default templates to include.
     *
     * @param string $paramString Complete parameter string
     *
     * @return string
     * @todo  Make this more dev friendly
     */
    public function meta(string $paramString): ?string
    {
        return $this->execute('meta', $paramString);
    }

    /**
     * Show version information
     */
    public function version(): string
    {
        return $this->execute('version');
    }
}
