<?template scope="entity"?>
{% if entity.role == 'lookup' %}
<?php
/**
 * {{ project.title }} {{ entity.name | title }} List Form Field
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

defined('JPATH_BASE') or die;

/**
 * {{ entity.name | title }} List Field Class for the Joomla Framework.
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}
 * @since      1.0.0
{# endalign #}
 */
class JFormFieldList_{{ entity.name | plural | class }} extends JFormField
{
	/**
	 * The form field type.
	 * @var  string
	 */
	protected $type = 'List_{{ entity.name | plural | class }}';
{# override properties #}{# endoverride #}
{# override getInput #}

	/**
	 * Get the field input markup.
	 *
{# align("\t", '  ') #}
	 * @return  string  The field input markup
{# endalign #}
	 */
	protected function getInput()
	{
		JFactory::getDocument()->addStyleDeclaration(
			'.lookup_table {width:100%;height:360px;}' . '#jform_{{ entity.name | singular | file }}_list-lbl {display: none;}'
		);

		$link = 'index.php?option=com_{{ project.name | file }}&amp;view={{ entity.name | plural | file }}&amp;layout=modal&amp;tmpl=component';
		$html = '<iframe class="lookup_table" src="' . $link . '"></iframe>';
		return $html;
	}
{# endoverride #}
{# override methods #}{# endoverride #}
}
{% endif %}
