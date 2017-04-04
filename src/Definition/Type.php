<?php

namespace GreenCape\CodeGen\Definition;

class Type
{
    private $type;
    private $sign;
    private $len;
    private $null;
    private $input = '';
    private $role  = '';
    private $index = '';
    private $validation = [];

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
     *
     * @return mixed
     */
    private function init($config)
    {
        if (is_string($config)) {
            $this->init($this->resolve($config));

            return;
        }

        switch ($config['type'] ?? 'string') {
            case 'boolean':
                $this->type  = 'boolean';
                $this->len   = $config['len'] ?? 1;
                $this->input = 'yesno';
                break;

            case 'csv':
                $this->type  = 'string';
                $this->len   = $config['len'] ?? 255;
                $this->input = 'text';
                break;

            case 'date':
                $this->type  = 'date';
                $this->len   = $config['len'] ?? 10;
                $this->input = 'calendar';
                break;

            case 'id':
                $this->type  = 'integer';
                $this->len   = 10;
                $this->input = 'none';
                $this->role  = $config['role'] ?? 'key';
                $this->index = $config['index'] ?? 'unique';
                $this->validation['positive'] = true;
                break;

            case 'integer':
                $this->type  = 'integer';
                $this->len   = 10;
                $this->input = 'number';
                break;

            case 'json':
                $this->type  = 'string';
                $this->len   = 255;
                $this->input = 'text';
                break;

            case 'password':
                $this->type  = 'string';
                $this->len   = 64;
                $this->input = 'password';
                break;

            case 'richtext':
                $this->type  = 'string';
                $this->len   = 4096;
                $this->input = 'editor';
                break;

            case 'select':
                $this->type  = 'string';
                $this->len   = 64;
                $this->input = 'select';
                break;

            case 'string':
                $this->type  = 'string';
                $this->len   = 255;
                $this->input = 'text';
                break;

            case 'title':
                $this->type  = 'string';
                $this->len   = 64;
                $this->input = 'text';
                $this->role  = $config['role'] ?? 'title';
                $this->index = $config['index'] ?? 'unique';
                break;

            default:
                $this->type  = $config['type'] ?? 'string';
                $this->len   = $config['len'] ?? 0;
                $this->input = $config['input'] ?? '';
                break;
        }

        $this->sign = $config['sign'] ?? '';
        $this->null = $config['null'] ?? 'true';
    }

    private function resolve($config)
    {
        return [
            'type' => $config,
        ];
    }

    public function get($property)
    {
        return $this->{$property};
    }
}
