<?php

namespace GreenCape\CodeGen\Definition;

use RuntimeException;

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
        if ($this->hasGetter($property)) {
            return $this->{'get' . ucfirst($property)}();
        }

        return $this->{$property};
    }

    /**
     * Write Guard
     *
     * @param string $property
     * @param mixed  $value
     *
     * @throws RuntimeException
     */
    public function __set(string $property, $value)
    {
        throw new RuntimeException('Properties are read-only', 9001);
    }

    /**
     * @param string $property
     *
     * @return bool
     */
    private function hasGetter(string $property): bool
    {
        return method_exists($this, 'get' . ucfirst($property));
    }

    /**
     * @param string $property
     *
     * @return bool
     */
    public function __isset(string $property): bool
    {
        return $this->hasGetter($property) || $this->hasProperty($property);
    }

    /**
     * @param string $property
     *
     * @return bool
     */
    private function hasProperty(string $property): bool
    {
        return (bool) property_exists($this, $property);
    }
}
