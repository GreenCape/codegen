<?php

namespace GreenCape\CodeGen\Definition;

class Type
{
    private $type;
    private $sign;
    private $len;
    private $null;
    private $input;
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

    /**
     * @param $config
     */
    private function init($config)
    {
        if (is_string($config)) {
            $this->init($this->resolve($config));
        }

        $this->len   = $config['len'] ?? 255;
        $this->type  = $config['type'] ?? 'string';
        $this->mysql = $this->type;
        $this->php   = $this->type;
        $this->sign  = $config['sign'] ?? '';
        $this->null  = $config['null'] ?? 'true';
    }

    private function resolve($config)
    {
        return ['type' => $config,];
    }

    public function get($property)
    {
        return $this->{$property};
    }
}
