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

namespace {{ project.name | class }}\Entity;

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
    /** @var  {{ property.type }}  {{ property.description }} */
    public ${{ property.name | variable }};

{% endfor %}
}
