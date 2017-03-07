<?template scope="entity"?>
{% if entity.references.categories %}
<?php
/**
 * {{ project.title }} Categories Form Field
 *
 * PHP version 5.3
 *
{# align("\t", '  ') #}
 * @version    {{ project.version }}
 * @package    {{ project.name | class }}
 * @author     {{ author.name }} <{{ author.email | lower }}>
 * @copyright  Copyright (C){{ "now" | date('Y') }} {{ copyright }}. All rights reserved.
 * @license    {{ project.license }}
{# endalign #}
 */

// No direct access
defined('_JEXEC') or die;

/**
 * Categories Field Class for the Joomla Framework.
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}
 * @since      1.0.0
{# endalign #}
 */
class JFormFieldCategory extends JFormFieldList
{
	/**
	 * The form field type
	 * @var  string
	 */
	protected $type = 'Category';
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

		$query->select("`id` AS `value`, `title` AS `text`");
		$query->from("`#__categories`");
		$query->where("extension='com_{{ project.name | file }}'");
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
