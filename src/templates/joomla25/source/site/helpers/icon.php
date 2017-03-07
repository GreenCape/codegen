<?template scope="application"?>
<?php
/**
 * {{ project.title }} HTML Helper
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
 * {{ project.title }} HTML Helper
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}
 * @since      1.0.0
{# endalign #}
 */
class JHtmlIcon
{
{# override properties #}{# endoverride #}
{# override create #}

	/**
	 * Display an add icon
	 *
{# align("\t", '  ') #}
	 * @param	object	$category	  The containing category (if any)
	 * @param	string	$view	  The name of the current view
	 * @param	object	$params	  The article parameters
     * @param	array	$attribs	  Additional attributes
	 *
	 * @return  string  The HTML for the add icon
{# endalign #}
	 */
	public static function create($category, $view, $params, $attribs = array())
	{
		$uri = JFactory::getURI();

		$url = 'index.php?option=com_{{ project.name | file }}&task=' . $view . '.add&return=' . base64_encode($uri) . '&id=0';

		if (!empty($category))
		{
			$url .= '&catid=' . $category->id;
		}

		$url .= {{ project.name | class }}HelperRoute::getItemId();

		if ($params->get($view . '_show_icons'))
		{
			$text = JHtml::_('image', 'system/new.png', JText::_('JNEW'), null, true);
		}
		else
		{
			$text = JText::_('JNEW') . ' ';
		}

		$button = JHtml::_('link', JRoute::_($url), $text, $attribs);

		$output = '<span class="hasTip" title="' . JText::_('COM_{{ project.name | constant }}_' . $view . '_NEW') . '">' . $button . '</span>';
		return $output;
	}
{# endoverride #}
{# override email #}

	/**
	 * Display an email icon
	 *
{# align("\t", '  ') #}
	 * @param	object	$item	  The article in question
	 * @param	string	$view	  The name of the current view
	 * @param	object	$params	  The article parameters
	 * @param	array	$attribs	  Additional attributes
	 *
	 * @return  string  The HTML for the email icon
{# endalign #}
	 */
	public static function email($item, $view, $params, $attribs = array())
	{
		require_once JPATH_SITE . '/components/com_mailto/helpers/mailto.php';

		$uri      = JURI::getInstance();
		$base     = $uri->toString(array('scheme', 'host', 'port'));
		$template = JFactory::getApplication()->getTemplate();

		switch (strtolower($view))
		{
{% for entity in project.entities %}
{% if entity.role != 'main' and entity.role != 'lookup' and entity.role != 'map' %}
			case '{{ entity.name | singular | file }}':
				$link = {{ project.name | class }}HelperRoute::get{{ entity.name | class }}Route($item->{{ entity.special.key.name }}{% if entity.special.category %}, $item->{{ entity.special.category.name }}{% endif %});
				break;

{% endif %}
{% endfor %}
{% for entity in project.entities %}
{% if entity.role == 'main' %}
			case '{{ entity.name | singular | file }}':
			default:
				$link = {{ project.name | class }}HelperRoute::get{{ entity.name | class }}Route($item->{{ entity.special.key.name }}{% if entity.special.category %}, $item->{{ entity.special.category.name }}{% endif %});
				break;
{% endif %}
{% endfor %}
		}

		$link     = $base . JRoute::_($link, false);
		$url      = 'index.php?option=com_mailto&tmpl=component&template=' . $template . '&link=' . MailToHelper::addLink($link);
		$status   = 'width=400,height=350,menubar=yes,resizable=yes';

		if ($params->get($view . '_show_icons'))
		{
			$text = JHtml::_('image', 'system/emailButton.png', JText::_('JGLOBAL_EMAIL'), null, true);
		}
		else
		{
			$text = ' ' . JText::_('JGLOBAL_EMAIL');
		}

		$attribs['title']   = JText::_('JGLOBAL_EMAIL');
		$attribs['onclick'] = "window.open(this.href,'win2','" . $status . "'); return false;";

		$output = JHtml::_('link', JRoute::_($url), $text, $attribs);

		return $output;
	}
{# endoverride #}
{# override edit #}

	/**
	 * Display an edit icon
	 *
	 * This icon will not display in a popup window, nor if the article is trashed.
	 * Edit access checks must be performed in the calling code.
	 *
{# align("\t", '  ') #}
	 * @param	object	$item	  The article in question.
	 * @param	string	$view	  The name of the current view
	 * @param	object	$params	  The article parameters
     * @param	array	$attribs	  Additional attributes
	 *
	 * @return  string  The HTML for the edit icon
{# endalign #}
	 */
	public static function edit($item, $view, $params, $attribs = array())
	{
		// Ignore if in a popup window.
		if ($params && $params->get('popup'))
		{
			return;
		}

		// Ignore if the state is negative (trashed).
		if ($item->state < 0)
		{
			return;
		}

		$user = JFactory::getUser();
		$uri  = JFactory::getURI();

		JHtml::_('behavior.tooltip');

		// Show checked_out icon if the article is checked out by a different user
		if (
			property_exists($item, 'checked_out')
			&& property_exists($item, 'checked_out_time')
			&& $item->checked_out > 0
			&& $item->checked_out != $user->get('id')
		)
		{
			$checkoutUser = JFactory::getUser($item->checked_out);
			$button       = JHtml::_('image', 'system/checked_out.png', null, null, true);
			$date         = JHtml::_('date', $item->checked_out_time);
			$tooltip      = JText::_('JLIB_HTML_CHECKED_OUT') . ' :: ' . JText::sprintf('COM_CONTENT_CHECKED_OUT_BY', $checkoutUser->name) . '<br />' . $date;
			return '<span class="hasTip" title="' . htmlspecialchars($tooltip, ENT_COMPAT, 'UTF-8') . '">' . $button . '</span>';
		}

		$url = sprintf(
			'index.php?option=com_{{ project.name | file }}&task=%s.edit&id=%d&return=%s',
			$view,
			$item->id,
			base64_encode($uri) . {{ project.name | class }}HelperRoute::getItemId()
		);
		$icon   = $item->state ? 'edit.png' : 'edit_unpublished.png';
		$text   = JHtml::_('image', 'system/' . $icon, JText::_('JGLOBAL_EDIT'), null, true);
		$button = JHtml::_('link', JRoute::_($url), $text, $attribs);
		$output = '<span title="' . JText::_('COM_{{ project.name | constant }}_' . $view . '_EDIT') . '">' . $button . '</span>';

		return $output;
	}
{# endoverride #}
{# override print_popup #}

	/**
	 * Display a print icon
	 *
{# align("\t", '  ') #}
	 * @param	object	$item	  The article in question.
	 * @param	string	$view	  The name of the current view
	 * @param	object	$params	  The article parameters
     * @param	array	$attribs	  Additional attributes
	 *
	 * @return  string  The HTML for the print icon
{# endalign #}
	 */
	public static function print_popup($item, $view, $params, $attribs = array())
	{
		switch (strtolower($view))
		{
{% for entity in project.entities %}
{% if entity.role != 'main' and entity.role != 'lookup' and entity.role != 'map' %}
			case '{{ entity.name | singular | file }}':
				$link = {{ project.name | class }}HelperRoute::get{{ entity.name | class }}Route($item->{{ entity.special.key.name }}{% if entity.special.category %}, $item->{{ entity.special.category.name }}{% endif %});
				break;

{% endif %}
{% endfor %}
{% for entity in project.entities %}
{% if entity.role == 'main' %}
			case '{{ entity.name | singular | file }}':
			default:
				$link = {{ project.name | class }}HelperRoute::get{{ entity.name | class }}Route($item->{{ entity.special.key.name }}{% if entity.special.category %}, $item->{{ entity.special.category.name }}{% endif %});
				break;
{% endif %}
{% endfor %}
		}

		$link .= '&tmpl=component&print=1&layout=default&page=' . $params->get('limitstart', '');

		$status = 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no';

		if ($params->get($view . '_show_icons'))
		{
			$text = JHtml::_('image', 'system/printButton.png', JText::_('JGLOBAL_PRINT'), null, true);
		}
		else
		{
			$text = JText::_('JGLOBAL_ICON_SEP') . ' ' . JText::_('JGLOBAL_PRINT') . ' ' . JText::_('JGLOBAL_ICON_SEP');
		}

		$attribs['title']   = JText::_('JGLOBAL_PRINT');
		$attribs['onclick'] = "window.open(this.href,'win2','" . $status . "'); return false;";
		$attribs['rel']     = 'nofollow';

		return JHtml::_('link', JRoute::_($link), $text, $attribs);
	}
{# endoverride #}
{# override print_screen #}

	/**
	 * Display a print icon on preview
	 *
{# align("\t", '  ') #}
	 * @param	object	$item	  The article in question.
	 * @param	string	$view	  The name of the current view
	 * @param	object	$params	  The article parameters
	 *
	 * @return  string  The HTML for the article edit icon
{# endalign #}
	 */
	public static function print_screen($item, $view, $params)
	{
		if ($params->get($view . '_show_icons'))
		{
			$text = JHtml::_('image', 'system/printButton.png', JText::_('JGLOBAL_PRINT'), null, true);
		}
		else
		{
			$text = JText::_('JGLOBAL_ICON_SEP') . ' ' . JText::_('JGLOBAL_PRINT') . ' ' . JText::_('JGLOBAL_ICON_SEP');
		}

		$link = '<a href="#" onclick="window.print();return false;">' . $text . '</a>';

		return $link;
	}
{# endoverride #}
{# override methods #}{# endoverride #}
}
