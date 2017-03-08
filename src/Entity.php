<?php

namespace GreenCape\CodeGen;

/**
 * Class Entity
 *
 * @package GreenCape\CodeGen
 *
 * @property $name
 * @property $role
 * @property $storage
 * @property $dynKey
 * @property $dynName
 * @property $filters
 * @property $properties
 */
class Entity
{
    /**
     * The name of the entity.
     *
     * @var string
     */
    private $name = '';

    /**
     * Optional. The function of this entity (table). If omitted, no special treatment is conducted.
     * Valid values are 'main', 'lookup', 'map' or nothing.
     *
     * @var string
     */
    private $role = '';

    /**
     * Information about the storage. Currently only the type 'default' is supported, requiring the declaration of a
     * table name.
     *
     * @var array
     */
    private $storage = [];

    /**
     * Optional. Ordered list of properties making up a composed primary key.
     * If the entity uses a compound key, you can declare the list of properties making up the key.
     * If omitted, a property with role 'key' is required.
     *
     * @var Property[]
     */
    private $dynKey = [];

    /**
     * Optional. Ordered list of properties making up a composed display name.
     * If the entity uses a compound name, you can declare the list of properties making up the display name.
     * If omitted, a property with role 'title' is required.
     *
     * @var Property[]
     */
    private $dynName = [];

    /**
     * Optional. A list of properties that may be used for filtering.
     *
     * @var Property[]
     */
    private $filters = [];

    /**
     * A list of the entity's properties. All properties are listed in the properties section.
     *
     * @var Property[]
     */
    private $properties = [];
    /**
     * Properties may have a special meaning, expressed by their role. All properties with a role are collected in
     * special, so it is possible to access the properties by role.
     *
     * @var Property[]
     */
    private $special = [];
    /**
     * Ordered list of properties to be displayed in list (table) views.
     *
     * @var Property[]
     */
    private $listFields = [];
    /**
     * List of foreign keys pointing to this entity.
     *
     * @var array
     */
    private $details = [];
    /**
     * List of foreign keys in this entity.
     *
     * @var Property[]
     */
    private $references = [];

    /**
     * Allow read access to non-public members
     */
    use ReadOnlyGuard;

    /**
     * Entity constructor.
     *
     * @param array $config The entity settings
     *
     * @throws \Exception
     */
    public function __construct(array $config)
    {
        $this->init($config);
    }

    private function init($properties)
    {
        $this->name    = $properties['name'] ?? 'unnamed';
        $this->role    = $properties['role'] ?? '';
        $this->storage = $properties['storage'] ?? [];

        foreach ($properties['properties'] ?? [] as $propertyDefinition) {
            $this->addProperty(new Property($propertyDefinition));
        }

        foreach ($properties['dynKey'] ?? [] as $propertyName) {
            $this->dynKey[] = $this->properties[$propertyName];
        }

        foreach ($properties['dynName'] ?? [] as $propertyName) {
            $this->dynName[] = $this->properties[$propertyName];
        }

        foreach ($properties['filters'] ?? [] as $propertyName) {
            $this->filters[] = $this->properties[$propertyName];
        }
    }

    /**
     * Add a property to the entity
     *
     * @param Property $property The property
     */
    public function addProperty(Property $property)
    {
        $this->properties[$property->getName()] = $property;

        if (!empty($property->getRole())) {
            $this->special[$property->getRole()] = $property;
        }

        if (!empty($property->getPosition())) {
            $this->listFields[$property->getPosition()] = $property;
        }

        if ($property->getType('base') == 'reference') {
            $this->references[$property->getType('table')][] = $property;
            $this->references['foreignKeys'][]               = $property;
        }
    }

    public function addDetail(Entity $entity, Property $property)
    {
        $this->details[] = ['entity' => $entity, 'reference' => $property];
    }

    /**
     * @return Property[]
     */
    public function getSpecial(): array
    {
        return $this->special;
    }

    /**
     * @return Property[]
     */
    public function getListFields(): array
    {
        return $this->listFields;
    }

    /**
     * @return array
     */
    public function getDetails(): array
    {
        return $this->details;
    }

    /**
     * @return Property[]
     */
    public function getReferences(): array
    {
        return $this->references;
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
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @return array
     */
    public function getStorage(): array
    {
        return $this->storage;
    }

    /**
     * @return Property[]
     */
    public function getDynKey(): array
    {
        return $this->dynKey;
    }

    /**
     * @return Property[]
     */
    public function getDynName(): array
    {
        return $this->dynName;
    }

    /**
     * @return Property[]
     */
    public function getFilters(): array
    {
        return $this->filters;
    }

    /**
     * @return Property[]
     */
    public function getProperties(): array
    {
        return $this->properties;
    }
}
