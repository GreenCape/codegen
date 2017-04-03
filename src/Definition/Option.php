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

    public function __construct($option)
    {
        $this->init($option);
    }

    /**
     * @param $option
     */
    private function init($option)
    {
        if (is_array($option)) {
            $this->key   = $option['key'];
            $this->value = $option['value'];

            return;
        }

        $this->key   = $option;
        $this->value = $option;
    }

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
