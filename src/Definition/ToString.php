<?php

namespace GreenCape\CodeGen\Definition;

trait ToString
{
    /**
     * @return string
     */
    public function __toString(): string
    {
        return str_replace([
            ' => ',
            'GreenCape\\CodeGen\\Definition\\'
        ], [
            ': ',
            ''
        ], print_r($this, true));
    }
}
