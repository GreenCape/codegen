<?php

namespace GreenCape\CodeGen;

class Inflector extends \Doctrine\Common\Inflector\Inflector
{
    /**
     * Apply a filter to a string
     *
     * @param string $filter
     * @param string $string
     *
     * @return string
     */
    public function apply(string $filter, $string = ''): string
    {
        if (empty($string)) {
            $string = sprintf('Missing %s name', $filter);
        }

        $map = [
            'class'     => 'className',
            'file'      => 'fileName',
            'namespace' => 'namespaceName',
        ];

        if (isset($map[$filter])) {
            $filter = $map[$filter];
        }

        return $this->$filter($string);
    }

    /**
     * Turn a string into a variable name according to the naming conventions
     *
     * @param string $string
     *
     * @return string
     */
    public function variable(string $string): string
    {
        return lcfirst($this->className($string));
    }

    /**
     * Turn a string into a class name according to the naming conventions
     *
     * @param string $string
     *
     * @return string
     */
    private function className(string $string): string
    {
        return str_replace(' ', '', $this->title($string));
    }

    /**
     * Turn a string into a title, i.e., start each word with an uppercase letter
     *
     * @param string $string
     *
     * @return string
     */
    public function title(string $string): string
    {
        return ucwords($this->split($string));
    }

    /**
     * Split a string into single words, separated by a single space
     *
     * @param string $string
     *
     * @return string
     */
    public function split(string $string): string
    {
        $string = implode(' ', preg_split('/(?<=[^A-Z_])(?=[A-Z])|(?<=[A-Z])(?=[A-Z][^A-Z_])/x', $string));

        return preg_replace('/[\W\s_]+/', ' ', $string);
    }

    /**
     * Turn a string into a table name according to the naming conventions
     *
     * @param string $string
     *
     * @return string
     */
    public function table(string $string): string
    {
        return strtolower(str_replace(' ', '_', $this->title($string)));
    }

    /**
     * Turn a string into a file name according to the naming conventions
     *
     * @param string $string
     *
     * @return string
     */
    public function dash(string $string): string
    {
        return strtolower(str_replace(' ', '-', $this->title($string)));
    }

    /**
     * Turn a string into a constant name according to the naming conventions
     *
     * @param string $string
     *
     * @return string
     */
    public function constant(string $string): string
    {
        return strtoupper(str_replace(' ', '_', $this->title($string)));
    }

    /**
     * Turn a word into singular form
     *
     * @param string $string The word in plural form
     *
     * @return string The word in singular form
     */
    public function singular(string $string): string
    {
        return self::singularize($string);
    }

    /**
     * Turn a word into plural form
     *
     * @param string $string The word in singular form
     *
     * @return string The word in plural form
     */
    public function plural($string): string
    {
        return self::pluralize($string);
    }

    /** @noinspection PhpUnusedPrivateMethodInspection
     * Turn a string into a namespace name according to the naming conventions
     *
     * @param string $string
     *
     * @return string
     */
    private function namespaceName(string $string): string
    {
        return str_replace(' ', '\\', $this->title($string));
    }

    /** @noinspection PhpUnusedPrivateMethodInspection
     * Turn a string into a file name according to the naming conventions
     *
     * @param string $string
     *
     * @return string
     */
    private function fileName(string $string): string
    {
        return strtolower(str_replace(' ', '_', $this->title($string)));
    }
}
