<?php

namespace GreenCape\CodeGen\Definition;

use Exception;
use RuntimeException;

/**
 * Class Relation
 *
 * @package GreenCape\CodeGen
 * @property-read string   $name
 * @property-read string   $type
 * @property-read Property $property
 * @property-read Entity   $entity
 * @property-read Property $reference
 * @property-read Entity   $map
 */
class Relation
{
    /**
     * *Optional.* The name of a (virtual) property to contain the related entity or entities.
     * If omitted, `name` is derived from the name of the related entity.
     *
     * @var string
     */
    private $name;

    /**
     * The type of relation, one of 'belongsTo', 'hasMany', 'hasOne' or 'hasManyThru'.
     *
     * @var string
     */
    private $type;

    /**
     * *Optional.* The property in the current entity used for the relation.
     * If omitted, the property with the `key` role is used.
     *
     * @var Property
     */
    private $property;

    /**
     * The related entity.
     *
     * @var Entity
     */
    private $entity;

    /**
     * *Optional.* The property in the other entity used for the relation.
     * If omitted, the property with the `key` role is used
     *
     * @var Property
     */
    private $reference;

    /**
     * *Optional.* For 'hasManyThru' relations, a `map` entity may be specified. If omitted, it will be derived from
     * the entities involved (entity names in alphabetic order with a 'Map' suffix).
     *
     * @var Entity
     */
    private $map;

    /**
     * *Optional.* If set, `property` is expected to contain one (`hasOne`) or more
     * (`hasMany`) entities encoded using the given format (JSON, csv).
     * Will be ignored, if `reference` is provided.
     *
     * @var string
     */
    private $format;

    /**
     * @var string
     */
    private $constraint;

    /**
     * The entity registry
     *
     * @var Registry
     */
    private $registry;

    /**
     * Allow read access to non-public members
     */
    use ReadOnlyGuard;

    /**
     * Provide a __toString implementation
     */
    use ToString;

    /**
     * Relation constructor.
     *
     * @param array    $config
     * @param Entity   $entity
     * @param Registry $registry
     *
     * @throws Exception
     */
    public function __construct(array $config, Entity $entity, Registry $registry)
    {
        $this->registry = $registry;
        $this->init($config, $entity);
    }

    /**
     * @param array  $properties
     * @param Entity $current
     *
     * @throws Exception
     */
    private function init(array $properties, Entity $current): void
    {
        $this->name = $properties['name'] ?? strtolower($properties['entity']);

        if (empty($properties['type'])) {
            throw new RuntimeException('Relation type must be specified', 1204);
        }

        $this->type = $properties['type'];
        $this->constraint = $properties['constraint'] ?? '';

        if (empty($properties['property'])) {
            $this->property = $current->getSpecial()['key'];
        } elseif (is_string($properties['property'])) {
            $this->property = $current->getProperties()[$properties['property']];
        } else {
            $this->property = $properties['property'];
        }

        /*
         * Preset with placeholders, because it could be an external entity. Since the entity is created without the
         * registry, it will be overwritten, if a definition for the entity is encountered.
         */
        $this->entity    = new Entity(['name' => $properties['entity']]);
        $this->reference = new Property(['name' => $properties['reference'] ?? 'id']);

        $this->registry->registerCallback($properties['entity'], function (Entity $entity) use ($properties, $current) {
            $this->entity = $entity;

            if (empty($properties['reference'])) {
                if (!empty($properties['format'])) {
                    $this->format = $properties['format'];
                } else {
                    $this->reference = $entity->getSpecial()['key'];
                }
            } else {
                if (is_string($properties['reference'])) {
                    $entity->getProperties()[$properties['reference']];
                } else {
                    throw new Exception("Relation definition error: 'reference' must be a string or an array (defined in entity {$current->getName()}, relation {$this->type} {$this->name})");
                }
            }
        });

        if (empty($properties['map'])) {
            return;
        }

        $this->registry->registerCallback($properties['map'], function (Entity $entity) {
            $this->map = $entity;
        });
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getConstraint(): string
    {
        return $this->constraint;
    }

    /**
     * @return Property
     */
    public function getProperty(): Property
    {
        return $this->property;
    }

    /**
     * @return Entity
     */
    public function getEntity(): Entity
    {
        return $this->entity;
    }

    /**
     * @return Property
     */
    public function getReference(): Property
    {
        return $this->reference;
    }

    /**
     * @return Entity|null
     */
    public function getMap(): ?Entity
    {
        return $this->map;
    }
}
