<?template scope="entity"?>
<?php
/**
 * {{ project.title }} {{ entity.name | title }} Data Transfer Object
 *
 * PHP version 5.3
 *
{# align("\t", '  ') #}
 * @version    {{ project.version }}
 * @package    {{ project.name | class }}
{% for author in project.authors %}
 * @author     {{ author.name }} <{{ author.email | lower }}>
{% endfor %}
 * @copyright  Copyright (C){{ "now" | date('Y') }} {{ project.copyright }}. All rights reserved.
 * @license    {{ project.license }}
{# endalign #}
 */

namespace {{ project.name | namespace }}\Entity;

/**
 * {{ project.title }} {{ entity.name | title }} Data Transfer Object
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}
 * @since      1.0.0
{# endalign #}
 */
class {{ entity.name | class }}
{
{% for property in entity.properties %}
    /**
{% if property.description %}
     * {{ property.description }}
     *
{% endif %}
     * @var  {{ property.type }}
     */
    protected ${{ property.name | variable }};

{% endfor %}
{% for property in entity.properties %}
    /**
     * Gets the {{ property.name | title }}.
     *
     * @return  {{ property.type }}
     */
    public function get{{ property.name | class }}()
    {
        return $this->{{ property.name | variable }};
    }

    /**
     * Sets the {{ property.name | title }}.
     *
     * @param  {{ property.type }} ${{ property.name | variable }}
     */
    public function set{{ property.name | class }}(${{ property.name | variable }})
    {
        $this->{{ property.name | variable }} = ${{ property.name | variable }};
    }

{% endfor %}
}
