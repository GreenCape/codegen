<?php

namespace GreenCape\CodeGen\Definition;

/**
 * Trait ReadOnlyGuard
 *
 * Allows read access to non-public members. Supports getters.
 *
 * @package GreenCape\CodeGen
 */
trait ReadOnlyGuard
{
    /**
     * Read Accessor (for Twig)
     *
     * @param string $property The requested property
     *
     * @return mixed The property value
     */
    public function __get($property)
    {
        if (method_exists($this, 'get' . ucfirst($property))) {
            return call_user_func([$this, 'get' . ucfirst($property)]);
        }

        return $this->{$property};
    }

    /**
     * Write Guard
     *
     * @param string $property
     * @param mixed $value
     *
     * @throws \Exception
     */
    public function __set($property, $value)
    {
        throw new \Exception('Properties are read-only', 9001);
    }
}
