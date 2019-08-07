<?php

namespace GreenCape\CodeGen\Definition;

use Exception;
use RuntimeException;

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
     * The parent project
     *
     * @var Project
     */
    private $project;

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
     * A list of forms, i.e., field groups.
     *
     * @var []
     */
    private $forms = [];

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
     * @var Property[][]
     */
    private $references = [];

    /**
     * List of known relations for this entity.
     *
     * @var Relation[]
     */
    private $relations = [];

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
     * Entity constructor.
     *
     * @param array    $config The entity settings
     * @param Registry $registry
     *
     * @throws Exception
     */
    public function __construct(array $config, Registry $registry = null)
    {
        $this->registry = $registry;
        $this->init($config);
    }

    /**
     * @param array $properties
     *
     * @throws Exception
     */
    private function init(array $properties): void
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

        if (!empty($this->registry)) {
            $this->registry->registerEntity($this);
        }

        foreach ($properties['relations'] ?? [] as $relationDefinition) {
            if (!isset($relationDefinition['property'])) {
                $relationDefinition['property'] = $this->special['key'] ?? 'id';
            }
            $this->addRelation(new Relation($relationDefinition, $this, $this->registry), $relationDefinition['entity']);
        }
    }

    /**
     * Add a property to the entity
     *
     * @param Property $property The property
     */
    public function addProperty(Property $property): void
    {
        $this->properties[$property->getName()] = $property;

        if (!empty($property->getRole())) {
            $this->special[$property->getRole()] = $property;
        }

        if (!empty($property->getPosition())) {
            $this->listFields[$property->getPosition()] = $property;
        }

        if (!empty($property->getForm())) {
            if (!isset($this->forms[$property->getForm()])) {
                $this->forms[$property->getForm()] = [
                    'name' => $property->getForm(),
                    'properties' => [$property]
                ];
            }
            else
            {
                $this->forms[$property->getForm()]['properties'][] = $property;
            }
        }
    }

    /**
     * Add a relation to the entity
     *
     * @param Relation $relation
     * @param string   $entityName
     *
     * @throws RuntimeException
     */
    public function addRelation(Relation $relation, string $entityName): void
    {
        $this->relations[$relation->getName()] = $relation;

        switch ($relation->getType()) {

            case 'belongsTo':
                $this->references[$entityName][]   = $relation;
                $this->references['foreignKeys'][] = $relation;
                break;

            case 'hasMany':
            case 'hasOne':
                $this->details[] = [
                    'entity'    => $relation->getEntity(),
                    'reference' => $relation->getReference(),
                ];
                break;

            case 'hasManyThru':
                $this->details[] = [
                    'entity'    => $relation->getEntity(),
                    'map'       => $relation->getMap(),
                    'reference' => $relation->getReference(),
                ];
                break;

            default:
                throw new RuntimeException("Unknown relation type '{$relation->getType()}'", 1203);
                break;
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Add a project to the entity
     *
     * @param Project $project The project
     */
    public function setProject(Project $project): void
    {
        $this->project = $project;
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
     * @return Property[][]
     */
    public function getReferences(): array
    {
        return $this->references;
    }

    /**
     * @return Relation[]
     */
    public function getRelations(): array
    {
        return $this->relations;
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
