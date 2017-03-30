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
     */
    public function configHelp($language)
    {
        return $this->execute('config-help');
    }

    /**
     * Generate code with chosen lang
     *
     * @param string $paramString Complete parameter string
     *
     * @todo  Make this more dev friendly
     */
    public function generate($paramString)
    {
        return $this->execute('generate', $paramString);
    }

    /**
     * Display help information
     *
     * @param string $command
     */
    public function help($command)
    {
        return $this->execute('help', $command);
    }

    /**
     * Show available languages
     */
    public function languages()
    {
        return $this->execute('langs');
    }

    /**
     * MetaGenerator.
     *
     * Generator for creating a new template set and configuration for Codegen.
     * The output will be based on the language you specify, and includes default templates to include.
     *
     * @param string $paramString Complete parameter string
     *
     * @todo  Make this more dev friendly
     */
    public function meta($paramString)
    {
        return $this->execute('meta', $paramString);
    }

    /**
     * Show version information
     */
    public function version()
    {
        return $this->execute('version');
    }

    /**
     * @param $command
     * @param $paramString
     *
     * @return mixed
     */
    private function execute($command, $paramString = '')
    {
        $dir    = getcwd();
        $userId = getmyuid() . ':' . getmygid();
        $volume = "--volume $dir:/local";
        $image  = 'swaggerapi/swagger-codegen-cli';
        $output = [];
        $return = 0;

        $dockerCommand = "docker run --rm $volume --user $userId --name temp_swagger $image {$command} {$paramString}";
        exec($dockerCommand, $output, $return);

        return $output;
    }
}
