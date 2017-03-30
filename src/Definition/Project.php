<?php

namespace GreenCape\CodeGen\Definition;

use GreenCape\CodeGen\Inflector;

/**
 * Class Project
 *
 * @package GreenCape\CodeGen
 *
 * @property $name
 * @property $title
 * @property $version
 * @property $build
 * @property $description
 * @property $type
 * @property $copyright
 * @property $license
 * @property $authors
 * @property $entities
 */
class Project
{
    /**
     * The name of the project (package). Should be URL friendy, i.e., all lowercase without spaces.
     * If omitted, the name is derived from the title. At least one of name or title must be declared.
     *
     * @var string
     */
    private $name = '';

    /**
     * The title of the project. If omitted, the title is derived from the name.
     * At least one of name or title must be declared.
     *
     * @var string
     */
    private $title = '';

    /**
     * Optional. The version number for the project.
     *
     * @var string
     */
    private $version = '0.0.0';

    /**
     * Optional. A build number. If present, the generator will increase it after every successful execution.
     *
     * @var int
     */
    private $build = 0;

    /**
     * Optional. A short description of the project. Should be less than 256 characters.
     *
     * @var string
     */
    private $description = '';

    /**
     * Optional. The type of the project.
     *
     * @var string
     */
    private $type = '';

    /**
     * Optional. The name of the copyright holder.
     *
     * @var string
     */
    private $copyright = '';

    /**
     * Optional. The license for the project.
     *
     * @var string
     */
    private $license = '';

    /**
     * Optional. A list of name and email address of the authors.
     *
     * @var array
     */
    private $authors = [];

    /**
     * The list of entities used in this project.
     *
     * @var Entity[]
     */
    private $entities = [];

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
     * Project constructor.
     *
     * @param array $config The project settings
     *
     * @throws \Exception
     */
    public function __construct(array $config)
    {
        $this->registry = new Registry();
        $this->init($config);
    }

    /**
     * Initialise the project properties
     *
     * @param  array $properties The properties
     *
     * @throws \Exception
     */
    private function init(array $properties)
    {
        $this->name  = $properties['name'] ?? '';
        $this->title = $properties['title'] ?? '';

        if (empty($this->name) && empty($this->title)) {
            throw new \Exception('One of "name" or "title" must be set.', 1101);
        }

        if (empty($this->name) || empty($this->title)) {
            $inflector = new Inflector();

            if (empty($this->title)) {
                $this->title = $inflector->title($this->name);
            }
            else {
                $this->name = $inflector->fileName($this->title);
            }
        }

        $this->version     = $properties['version'] ?? '0.0.0';
        $this->build       = $properties['build'] ?? 0;
        $this->description = $properties['description'] ?? '';
        $this->type        = $properties['type'] ?? 'application';
        $this->copyright   = $properties['copyright'] ?? 'the authors';
        $this->license     = $properties['license'] ?? 'Proprietary License';
        $this->authors     = $properties['authors'] ?? [];

        foreach ($properties['entities'] ?? [] as $entityDefinition) {
            if (empty($entityDefinition)) {
                continue;
            }
            $this->addEntity(new Entity($entityDefinition, $this->registry));
        }
    }

    /**
     * Add an entity to the project
     *
     * @param Entity $entity
     */
    public function addEntity(Entity $entity)
    {
        $entity->setProject($this);
        $this->entities[$entity->name] = $entity;
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
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @return int
     */
    public function getBuild(): int
    {
        return $this->build;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
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
    public function getCopyright(): string
    {
        return $this->copyright;
    }

    /**
     * @return string
     */
    public function getLicense(): string
    {
        return $this->license;
    }

    /**
     * @return array
     */
    public function getAuthors(): array
    {
        return $this->authors;
    }

    /**
     * @return Entity[]
     */
    public function getEntities()
    {
        return $this->entities;
    }
}
