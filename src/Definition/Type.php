<?php

namespace GreenCape\CodeGen\Definition;

class Type
{
    private $base;
    private $sign;
    private $len;
    private $null;
    private $mysql;
    private $php;

    /**
     * Allow read access to non-public members
     */
    use ReadOnlyGuard;

    public function __construct($config)
    {
        $this->init($config);
    }

    public function get($property)
    {
        return $this->{$property};
    }

    /**
     * @param $config
     */
    private function init($config)
    {
        if (is_string($config)) {
            $this->init($this->resolve($config));
        }
        $this->base = $config['base'] ?? 'string';
        $this->sign = $config['sign'] ?? '';
        $this->len  = $config['len'] ?? 255;
        $this->null = $config['null'] ?? 'true';

        $this->mysql = 'MYSQL_TYPE';
        $this->php   = 'PHP_TYPE';
    }

    private function resolve($config)
    {
        return ['base' => $config,];
    }
}
