<?template scope="entity"?>
{% if entity.role != 'lookup' and entity.role != 'map' %}
<?php
/**
 * {{ project.title }} {{ entity.name | title }} Frontend List Controller
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

// No direct access.
defined('_JEXEC') or die;

/**
 * {{ entity.name | plural | class }} Frontend Controller
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}
 * @since      1.0.0
{# endalign #}
 */
class {{ project.name | class }}Controller{{ entity.name | plural | class }} extends JController
{
{# override properties #}{# endoverride #}
{# override __construct #}{# endoverride #}
{# override execute #}{# endoverride #}
{# override getModel #}

	/**
	 * Get a model object, loading it if required
	 *
{# align("\t", '  ') #}
	 * @param	string	$name	  The model name. Optional.
	 * @param	string	$prefix	  The class prefix. Optional.
	 *
	 * @return  JModel  The model
{# endalign #}
	 */
	public function getModel($name = '{{ entity.name | plural | class }}', $prefix = '{{ project.name | class }}Model')
	{
		return parent::getModel($name, $prefix, array('ignore_request' => true));
	}
{# endoverride #}
{# override methods #}{# endoverride #}
}
{% endif %}
