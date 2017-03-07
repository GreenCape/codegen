<?template scope="application"?>
<?php
/**
 * {{ project.title }} Admin Component Helper
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
 * {{ project.title }} Component Helper
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}
 * @since      1.0.0
{# endalign #}
 */
class {{ project.name | class }}AdminHelper
{
{# override properties #}{# endoverride #}
{# override addSubmenu #}

	/**
	 * Configure the link bar
	 *
{# align("\t", '  ') #}
	 * @param	string	$viewName	  The name of the active view
	 *
	 * @return  void
{# endalign #}
	 */
	public static function addSubmenu($viewName = '')
	{
{% for entity in project.entities %}
{% if entity.role == 'main' %}
		JSubMenuHelper::addEntry(
			JText::_('COM_{{ project.name | constant }}_{{ entity.name | constant }}_SUBMENU'),
			'index.php?option=com_{{ project.name | file }}&view={{ entity.name | plural | file }}',
			$viewName == '{{ entity.name | plural | file }}'
		);

{% if entity.special.category %}
		JSubMenuHelper::addEntry(
			JText::_('COM_{{ project.name | constant }}_{{ entity.name | constant }}_CATEGORIES'),
			'index.php?option=com_categories&extension=com_{{ project.name | file }}',
			$viewName == 'categories'
		);

		if ($viewName == 'categories')
		{
			JToolBarHelper::title(
				JText::sprintf('COM_{{ project.name | constant }}_CATEGORIES_TITLE', JText::_('com_{{ project.name | file }}')),
				'{{ entity.name | plural | file }}-categories'
			);
		}

{% endif %}
{% endif %}
{% endfor %}
{% for entity in project.entities %}
{% if entity.role != 'main' and entity.role != 'map' and entity.role != 'lookup' %}
		JSubMenuHelper::addEntry(
			JText::_('COM_{{ project.name | constant }}_{{ entity.name | constant }}_SUBMENU'),
			'index.php?option=com_{{ project.name | file }}&view={{ entity.name | plural | file }}',
			$viewName == '{{ entity.name | plural | file }}'
		);

{% endif %}
{% endfor %}
		return;
	}
{# endoverride #}
{# override getActions #}
{% for entity in project.entities %}
{% if entity.role == 'main' %}

	/**
	 * Get a list of the actions that can be performed
	 *
{# align("\t", '  ') #}
{% if entity.special.category %}
	 * @param	int	$categoryId	  The category ID
{% endif %}
	 * @param   int  ${{ entity.name | singular | file }}  The {{ entity.name | title }} ID
	 *
	 * @return  JObject
{# endalign #}
	 */
	public static function getActions({% if entity.special.category %}$categoryId = 0, {% endif %}${{ entity.name | singular | file }} = 0)
	{
		$user   = JFactory::getUser();
		$result = new JObject;

		if (empty(${{ entity.name | singular | file }}){% if entity.special.category %} && empty($categoryId){% endif %})
		{
			$assetName = 'com_{{ project.name | file }}';
		}
{% if entity.special.category %}
		elseif (empty(${{ entity.name | singular | file }}))
		{
			$assetName = 'com_{{ project.name | file }}.category.' . (int) $categoryId;
		}
{% endif %}
		else
		{
			$assetName = 'com_{{ project.name | file }}.{{ entity.name | singular | file }}.' . (int) ${{ entity.name | singular | file }};
		}

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action)
		{
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}
{% endif %}
{% endfor %}
{# endoverride #}
{# override truncate #}

	/**
	 * Truncate a string
	 *
	 * If the string is shortened, a horizontal ellipsis is added, and the string is surrounded by a
	 * span with a title containing the complete string.
	 *
	 * ATTN: This method is *not* HTML safe.
	 *
{# align("\t", '  ') #}
	 * @param	string	$str	  The string to be truncated
	 * @param	int	$len	  The maximum length of the truncated string
	 *
	 * @return  string
{# endalign #}
	 */
	public static function truncate($str, $len = 50)
	{
		$str = trim($str);
		$truncated = trim(JString::substr($str, 0, $len - 1));
		if ($truncated != $str)
		{
			$truncated = '<span title="' . htmlspecialchars($str) . '">' . htmlspecialchars($truncated) . '&hellip;</span>';
		}
		return $truncated;
	}
{# endoverride #}
{# override methods #}{# endoverride #}
}
