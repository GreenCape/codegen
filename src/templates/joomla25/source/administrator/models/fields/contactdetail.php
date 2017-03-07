<?template scope="entity"?>
{% if entity.references.contact_details %}
<?php
/**
 * {{ project.title }} Contact Details Form Field
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
 * Contact Details Field Class for the Joomla Framework.
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}
 * @since      1.0.0
{# endalign #}

 */
class JFormFieldContactDetail extends JFormFieldList
{
	/**
	 * The form field type
	 * @var  string
	 */
	protected $type = 'ContactDetail';
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

		$query->select("`id` AS `value`, `name` AS `text`");
		$query->from("`#__contact_details`");
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
