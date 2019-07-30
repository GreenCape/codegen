<?php

namespace GreenCape\CodeGen\Definition;

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

    /**
     * Validation constructor.
     *
     * @param string|array $rule
     */
    public function __construct($rule)
    {
        if (is_string($rule)) {
            $this->rule  = $rule;
            $this->value = true;

            return;
        }

        if (array_key_exists('rule', $rule)) {
            $this->rule  = $rule['rule'];
            $this->value = $rule['value'];

            return;
        }

        reset($rule);
        $this->rule  = key($rule);
        $this->value = $rule[$this->rule];
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
