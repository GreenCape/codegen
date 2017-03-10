<?php

namespace GreenCape\CodeGen\Definition;

class Registry
{
    /**
     * The registered entities
     *
     * @var Entity[]
     */
    private $entities = [];

    /**
     * The deferred callbacks
     *
     * @var callable[]
     */
    private $callbacks = [];

    /**
     * Registers an Entity.
     *
     * After registration, all outstanding callbacks are resolved.
     *
     * @param Entity $entity
     */
    public function registerEntity(Entity $entity)
    {
        $entityName                  = $entity->getName();
        $this->entities[$entityName] = $entity;

        if (isset($this->callbacks[$entityName])) {
            foreach ($this->callbacks[$entityName] as $f) {
                $f($entity);
            }

            unset($this->callbacks[$entityName]);
        }
    }

    /**
     * Registers a Callback for an Entity.
     *
     * If the entity is already registered, the callback is resolved immediately.
     * Otherwise, the callback is deferred until the entity gets registered.
     *
     * @param string   $entityName
     * @param callable $f
     */
    public function registerCallback(string $entityName, $f)
    {
        if (isset($this->entities[$entityName])) {
            $f($this->entities[$entityName]);
        } else {
            $this->callbacks[$entityName][] = $f;
        }
    }
}
