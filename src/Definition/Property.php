<?php

namespace GreenCape\CodeGen\Definition;

/**
 * Class Property
 *
 * @package GreenCape\CodeGen
 *
 * @property-read string       $name
 * @property-read Type         $type
 * @property-read int          $len
 * @property-read string       $role
 * @property-read string       $input
 * @property-read string       $label
 * @property-read string       $description
 * @property-read string       $hint
 * @property-read string       $translate
 * @property-read string       $search
 * @property-read mixed        $default
 * @property-read Option[]     $options
 * @property-read int          $position
 * @property-read string       $form
 * @property-read Validation[] $validation
 * @property-read string       $index
 */
class Property
{
    /**
     * The property name
     *
     * @var string
     */
    private $name;

    /**
     * The property type
     *
     * @var Type
     */
    private $type;

    /**
     * The property length
     *
     * @var integer
     */
    private $len;

    /**
     * Property roles allow to use any property name you want in your entities, and make the generator aware of its
     * special meaning.
     *
     * @var string
     */
    private $role;

    /**
     * @var string
     */
    private $input;

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $hint;

    /**
     * @var string
     */
    private $translate;

    /**
     * @var string
     */
    private $search;

    /**
     * @var mixed
     */
    private $default;

    /**
     * @var Option[]
     */
    private $options = [];

    /**
     * @var int
     */
    private $position;

    /**
     * @var string
     */
    private $form;

    /**
     * @var Validation[]
     */
    private $validation = [];

    /**
     * @var string
     */
    private $index;

    /**
     * Allow read access to non-public members
     */
    use ReadOnlyGuard;

    public function __construct(array $config)
    {
        $this->init($config);
    }

    private function init($property)
    {
        $this->name        = $property['name'] ?? 'unnamed';
        $this->type        = new Type($property);
        $this->len         = $this->type->get('len');
        $this->role        = $property['role'] ?? $this->type->get('role');
        $this->input       = $property['input'] ?? $this->type->get('input');
        $this->label       = $property['label'] ?? '';
        $this->description = $property['description'] ?? '';
        $this->hint        = $property['hint'] ?? '';
        $this->translate   = $property['translate'] ?? '';
        $this->search      = $property['search'] ?? '';
        $this->default     = $property['default'] ?? '';
        $this->position    = intval($property['position'] ?? 0);
        $this->form        = $property['form'] ?? 'record';
        $this->index       = $property['index'] ?? $this->type->get('index');

        foreach ($property['options'] ?? [] as $option) {
            $this->addOption(new Option($option));
        }

        foreach ($this->type->get('validation') as $rule => $value) {
            $this->addValidation(new Validation([$rule => $value]));
        }

        foreach ($property['validation'] ?? [] as $validation) {
            $this->addValidation(new Validation($validation));
        }
    }

    public function addOption(Option $option)
    {
        $this->options[$option->getKey()] = $option;
    }

    public function addValidation(Validation $validation)
    {
        $this->validation[$validation->getRule()] = $validation;
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
    public function getType($index = 'type'): string
    {
        return $this->type->get($index);
    }

    /**
     * @return int
     */
    public function getLen(): int
    {
        return $this->len;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @return string
     */
    public function getInput(): string
    {
        return $this->input;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
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
    public function getHint(): string
    {
        return $this->hint;
    }

    /**
     * @return string
     */
    public function getTranslate(): string
    {
        return $this->translate;
    }

    /**
     * @return string
     */
    public function getSearch(): string
    {
        return $this->search;
    }

    /**
     * @return mixed
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @return Option[]
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @return string
     */
    public function getForm(): string
    {
        return $this->form;
    }

    /**
     * @return Validation[]
     */
    public function getValidation(): array
    {
        return $this->validation;
    }

    /**
     * @return string
     */
    public function getIndex(): string
    {
        return $this->index;
    }
}
