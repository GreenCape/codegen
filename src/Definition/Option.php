<?php

namespace GreenCape\CodeGen\Definition;

class Option
{
    /**
     * The key (storage value) of the option
     *
     * @var int|string
     */
    private $key;

    /**
     * The value (display value) of the option
     *
     * @var string
     */
    private $value;

    /**
     * Allow read access to non-public members
     */
    use ReadOnlyGuard;

    /**
     * @return int|string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
