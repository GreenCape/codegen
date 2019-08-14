<?php

namespace GreenCape\CodeGen\Definition;

/**
 * Class Merger
 *
 * @package GreenCape\CodeGen
 */
class Merger
{
    /**
     * @var array
     */
    private $projectDefinition;

    /**
     * Merger constructor.
     *
     * @param array $projectDefinition
     */
    public function __construct(array $projectDefinition)
    {
        $this->projectDefinition = $projectDefinition;
    }

    /**
     * @param array $featureDefinition
     *
     * @return $this
     */
    public function merge(array $featureDefinition)
    {
        $requestedFeatures = $this->projectDefinition['supports'] ?? [];
        $providedFeatures  = array_keys($featureDefinition);

        $features = array_intersect($requestedFeatures, $providedFeatures);

        foreach ($this->projectDefinition['entities'] as $key => $entity) {
            foreach ($features as $feature) {
                foreach ($featureDefinition[$feature]['entities'] as $addition) {
                    $this->addFeature($entity, $addition, $key);
                }
            }
        }

        return $this;
    }

    /**
     * @param $entity
     * @param $addition
     * @param $key
     */
    private function addFeature($entity, $addition, $key): void
    {
        if (!empty($addition['include']) && !in_array($entity['role'] ?? '', $addition['include'], true)) {
            return;
        }
        if (!empty($addition['exclude']) && in_array($entity['role'] ?? '', $addition['exclude'], true)) {
            return;
        }
        if (empty($addition['name'])) {
            $this->addProperties($key, $addition);
        } else {
            $this->addEntity($addition);
        }
    }

    /**
     * @param $key
     * @param $addition
     */
    private function addProperties($key, $addition): void
    {
        $this->projectDefinition['entities'][$key] = array_merge_recursive($this->projectDefinition['entities'][$key], [
            'properties' => $addition['properties'] ?? [],
            'relations'  => $addition['relations'] ?? [],
            'filters'    => $addition['filters'] ?? [],
        ]);
    }

    /**
     * @param $addition
     */
    private function addEntity($addition): void
    {
        $this->projectDefinition['entities'][] = $addition;
    }

    /**
     * @return array
     */
    public function definition()
    {
        return $this->projectDefinition;
    }
}
