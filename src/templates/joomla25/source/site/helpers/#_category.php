<?template scope="entity"?>
{% if entity.role != 'lookup' and entity.role != 'map' and entity.special.category %}
<?php
/**
 * {{ project.title }} Frontend {{ entity.name | title }} Category Helper
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

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.categories');

/**
 * {{ project.title }} {{ entity.name | singular | title }} Category Helper
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}
 * @since      1.0.0
{# endalign #}
 */
class {{ project.name | class }}{{ entity.name | singular | class }}Categories extends JCategories
{
{# override properties #}{# endoverride #}
{# override __construct #}

	/**
	 * Constructor
	 *
{# align("\t", '  ') #}
	 * @param	array	$options	  Configuration options
{# endalign #}
	 */
	public function __construct($options = array())
	{
		$options['table']      = '#__{{ entity.storage.table }}';
		$options['extension']  = 'com_{{ project.name | file }}';
		$options['key']        = '{{ entity.special.key.name }}';
		$options['field']      = '{{ entity.special.category.name }}';
{% if entity.special.published %}
		$options['statefield'] = '{{ entity.special.published.name }}';
{% endif %}

		parent::__construct($options);

		return;
	}
{# endoverride #}
{# override methods #}{# endoverride #}
}
{% endif %}
