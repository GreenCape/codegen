<?template scope="entity"?>
{% if entity.details and entity.role != 'map' or entity.role == 'lookup' %}
<?php
/**
 * {{ project.title }} {{ entity.name | title }} Form Field
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

/**
 * {{ entity.name | title }} Field Class for the Joomla Framework.
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}
 * @since      1.0.0
{# endalign #}
 */
class JFormField{{ entity.name | class }} extends JFormFieldList
{
	/**
	 * The form field type
	 * @var  string
	 */
	protected $type = '{{ entity.name | class }}';
{# override properties #}{# endoverride #}
{# override getInput #}{# endoverride #}
{# override getOptions #}

	/**
	 * Get the field options
	 *
{# align("\t", '  ') #}
	 * @throws  Exception on database error
	 * @return  array  The field option objects
{# endalign #}
	 */
	public function getOptions()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
{# context mysql #}
		$query->select("`{{ entity.special.key.name }}` AS `value`, {% if not entity.special.language %}`{{ entity.special.title.name }}`{% else %}CONCAT(`{{ entity.special.title.name }}`, ' (', `{{ entity.special.language.name }}`, ')'){% endif %} AS `text`");
		$query->from("`#__{{ entity.storage.table }}`");
{# endcontext #}

		$query->order("`text` ASC");
		$db->setQuery($query);
		$options = $db->loadObjectList();

		// Merge any additional options in the XML definition
		if (isset($this->element))
		{
			$options = array_merge(parent::getOptions(), $options);
		}
		return $options;
	}
{# endoverride #}
{# override methods #}{# endoverride #}
}
{% endif %}
