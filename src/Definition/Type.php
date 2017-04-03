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

    public function get($property)
    {
        return $this->{$property};
    }

    /**
     * @param $config
     * @return mixed
     */
    private function init($config)
    {
        if (is_string($config)) {
            $this->init($this->resolve($config));
            return;
        }

        $this->len = $config['len'] ?? 255;

        switch ($config['type'] ?? 'string') {
            case 'password':
                $this->type = 'string';
                $this->input = 'password';
                $this->mysql = "VARCHAR({$this->len})";
                $this->php = $this->type;
                break;

            case 'richtext':
                $this->type = 'string';
                $this->input = 'editor';
                $this->mysql = 'MEDIUMTEXT';
                $this->php = $this->type;
                break;

            default:
                $this->type = $config['type'] ?? 'string';
                $this->mysql = $this->type;
                $this->php = $this->type;
                break;
        }

        $this->sign = $config['sign'] ?? '';
        $this->null = $config['null'] ?? 'true';
    }

    private function resolve($config)
    {
        return ['type' => $config,];
    }
}
