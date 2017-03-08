<?php

namespace GreenCape\CodeGen;

class Inflector extends \Doctrine\Common\Inflector\Inflector
{
    public function apply($filter, $string)
    {
        if ($filter == 'class') {
            $filter = 'className';
        }
        elseif ($filter == 'file') {
            $filter = 'fileName';
        }

        return call_user_func([$this, $filter], $string);
    }

    public function variable($string)
    {
        return lcfirst($this->className($string));
    }

    public function className($string)
    {
        return str_replace(' ', '', $this->title($string));
    }

    public function title($string)
    {
        return ucwords($this->split($string));
    }

    protected function split($string)
    {
        $string = implode(' ', preg_split('/(?<=[^A-Z_])(?=[A-Z])|(?<=[A-Z])(?=[A-Z][^A-Z_])/x', $string));
        $string = preg_replace('/[\s_-]+/', ' ', $string);

        return $string;
    }

    public function table($string)
    {
        return strtolower(str_replace(' ', '_', $this->title($string)));
    }

    public function fileName($string)
    {
        return strtolower(str_replace(' ', '_', $this->title($string)));
    }

    public function dash($string)
    {
        return strtolower(str_replace(' ', '-', $this->title($string)));
    }

    public function constant($string)
    {
        return strtoupper(str_replace(' ', '_', $this->title($string)));
    }

    public function singular($string)
    {
        return self::singularize($string);
    }

    public function plural($string)
    {
        return self::pluralize($string);
    }
}
