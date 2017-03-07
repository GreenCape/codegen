<?template scope="entity"?>
{% if entity.role == 'main' %}
<?php
/**
 * {{ project.title }} Frontend Controller
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
 * {{ project.title }} Frontend Controller
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}
 * @since      1.0.0
{# endalign #}
 */
class {{ project.name | class }}Controller extends JControllerLegacy
{
{# override properties #}{# endoverride #}
{# override __construct #}

	/**
	 * Constructor
	 *
{# align("\t", '  ') #}
	 * @param	array	$config	  A configuration array
{# endalign #}
	 */
	public function __construct($config = array())
	{
		$input = JFactory::getApplication()->input;

		if ($input->getCmd('view', null) === '{{ entity.name | singular | file }}' && $input->getCmd('layout', null) === 'pagebreak')
		{
			// Article frontpage Editor pagebreak proxying:
			$config['base_path'] = JPATH_COMPONENT_ADMINISTRATOR;
		}
		elseif ($input->getCmd('view', null) === '{{ entity.name | plural | file }}' && $input->getCmd('layout', null) === 'modal')
		{
			// Article frontpage Editor {{ entity.name | singular | title }} proxying:
			JHtml::_('stylesheet', 'system/adminlist.css', array(), true);
			$config['base_path'] = JPATH_COMPONENT_ADMINISTRATOR;
		}
		parent::__construct($config);
		return;
	}
{# endoverride #}
{# override methods #}{# endoverride #}
}
{% endif %}
