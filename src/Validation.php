<?php

namespace GreenCape\CodeGen;

class Validation
{
    /**
     * The validation rule
     *
     * @var string
     */
    private $rule;

    /**
     * The value for the rule
     *
     * @var int|string
     */
    private $value;

    /**
     * Allow read access to non-public members
     */
    use ReadOnlyGuard;

    public function __construct($rule, $value = null)
    {
        if (is_string($rule)) {
            $this->rule = $rule;
            $this->value = $value;
        } else {
            $this->rule  = $rule['rule'];
            $this->value = $rule['value'];
        }
    }

    /**
     * @return string
     */
    public function getRule(): string
    {
        return $this->rule;
    }

    /**
     * @return int|string
     */
    public function getValue()
    {
        return $this->value;
    }
}
