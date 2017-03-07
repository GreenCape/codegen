<?template scope="entity"?>
{% if entity.references.users %} 
<?php
/**
 * {{ project.title }} User Modal Form Field
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
 * User Modal Field Class for the Joomla Framework.
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}
 * @since      1.0.0
{# endalign #}
 */
class JFormFieldModal_User extends JFormField
{
	/**
	 * The form field type.
	 * @var  string
	 */
	protected $type = 'Modal_User';
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
		// Load the modal behavior script.
		JHtml::_('behavior.modal', 'a.modal');

		// Build the script.
		$script = array();

		$script[] = '	function jSelectUser_' . $this->id . '(id, title, object) {';
		$script[] = '		document.id("' . $this->id . '_id").value = id;';
		$script[] = '		document.id("' . $this->id . '_name").value = title;';
		$script[] = '		SqueezeBox.close();';
		$script[] = '	}';

		// Add the script to the document head.
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

		// Setup variables for display.
		$html = array();
		$link = 'index.php?option=com_users&amp;view=users&amp;layout=modal&amp;tmpl=component&amp;field=' . $this->id;

		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select("name");
		$query->from("`#__users`");
		$query->where('`id`=' . (int) $this->value);
		$db->setQuery($query);

		$title = $db->loadResult();
		if (empty($title))
		{
			$title = JText::_('JLIB_FORM_SELECT_USER');
		}
		$title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');

		// The current user display field.
		$html[] = '<div class="fltlft">';
		$html[] = '  <input type="text" id="' . $this->id . '_name" value="' . $title . '" disabled="disabled" size="35" />';
		$html[] = '</div>';

		// The user select button.
		$a_title = JText::_('JLIB_FORM_SELECT_USER');
		$a_href  = $link . '&amp;' . JSession::getFormToken() . '=1';
		$html[]  = '<div class="button2-left">';
		$html[]  = '  <div class="blank">';
		$html[]  = '    <a class="modal" title="' . $a_title . '" ' . ' href="' . $a_href . '" rel="{handler: \'iframe\', size: {x: 800, y: 450}}">'
			. JText::_('JLIB_FORM_CHANGE_USER')
			. '</a>';
		$html[]  = '  </div>';
		$html[]  = '</div>';

		if ((int) $this->value == 0)
		{
			$value = '';
		}
		else
		{
			$value = (int) $this->value;
		}

		// Set class='required' for client side validation
		$class = '';
		if ($this->required)
		{
			$class = ' class="required modal-value"';
		}

		$html[] = '<input type="hidden" id="' . $this->id . '_id"' . $class . ' name="' . $this->name . '" value="' . $value . '" />';

		return implode("\n", $html);
	}
{# endoverride #}
{# override methods #}{# endoverride #}
}
{% endif %}
