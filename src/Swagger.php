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
        `docker run --rm -u \${UID} -v \${PWD}:/local swaggerapi/swagger-codegen-cli config-help {$language}`;
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
        `docker run --rm -u \${UID} -v \${PWD}:/local swaggerapi/swagger-codegen-cli generate {$paramString}`;
    }

    /**
     * Display help information
     *
     * @param string $command
     */
    public function help($command)
    {
        `docker run --rm -u \${UID} -v \${PWD}:/local swaggerapi/swagger-codegen-cli help {$command}`;
    }

    /**
     * Show available languages
     */
    public function languages()
    {
        `docker run --rm -u \${UID} -v \${PWD}:/local swaggerapi/swagger-codegen-cli langs`;
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
        `docker run --rm -u \${UID} -v \${PWD}:/local swaggerapi/swagger-codegen-cli meta {$paramString}`;
    }

    /**
     * Show version information
     */
    public function version()
    {
        `docker run --rm -u \${UID} -v \${PWD}:/local swaggerapi/swagger-codegen-cli version`;
    }
}
