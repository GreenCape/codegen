<?php

namespace GreenCape\CodeGen;

/**
 * Class Project
 * @package GreenCape\CodeGen
 *
 * @property $properties
 * @property $entities
 */
class Project
{
    private $properties;

    private $entities = [];

    public function __construct($configFile)
    {
        if (!empty($configFile)) {
            $this->properties = json_decode(file_get_contents($configFile), true);
        }
    }

    public function __get($property)
    {
        if (method_exists($this, 'get' . ucfirst($property))) {
            return call_user_func([$this, 'get' . ucfirst($property)]);
        }
        return $this->properties[$property];
    }

    public function __set($property, $value)
    {
        if (method_exists($this, 'set' . ucfirst($property))) {
            return call_user_func([$this, 'set' . ucfirst($property)], $value);
        }
        return $this->properties[$property] = $value;
    }

    public function getProperties()
    {
        return $this->properties;
    }

    public function getEntities()
    {
        return $this->entities;
    }

    public function setEntities($entities)
    {
        $this->entities = $entities;
    }

    public function addEntity($entity)
    {
        $this->entities[] = $entity;
    }
}
