<?template scope="entity"?>
{% if entity.role != 'lookup' and entity.role != 'map' %}
<?php
/**
 * {{ project.title }} {{ entity.name | title }} Modal Form Field
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

defined('JPATH_BASE') or die;

/**
 * {{ entity.name | title }} Modal Field Class for the Joomla Framework.
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}
 * @since      1.0.0
{# endalign #}
 */
class JFormFieldModal_{{ entity.name | class }} extends JFormField
{
	/**
	 * The form field type.
	 * @var  string
	 */
	protected $type = 'Modal_{{ entity.name | class }}';
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

		$script[] = '	function jSelect{{ entity.name | class }}_' . $this->id . '(id, title, {% if entity.special.category %}catid, {% endif %}object) {';
		$script[] = '		document.id("' . $this->id . '_id").value = id;';
		$script[] = '		document.id("' . $this->id . '_name").value = title;';
		$script[] = '		SqueezeBox.close();';
		$script[] = '	}';

		// Add the script to the document head.
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

		// Setup variables for display.
		$html   = array();
		$menu   = JMenu::getInstance('site')->getActive();
		$Itemid = empty($menu) ? '' : ('&amp;Itemid=' . $menu->id);
		$link   = 'index.php?option=com_{{ project.name | file }}&amp;view={{ entity.name | plural | file }}&amp;layout=modal&amp;tmpl=component&amp;function=jSelect{{ entity.name | class }}_'
			. $this->id . $Itemid;

		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
{# context mysql #}
		$query->select("{% if not entity.special.language %}`{{ entity.special.title.name }}`{% else %}CONCAT(`{{ entity.special.title.name }}`, ' (', `{{ entity.special.language.name }}`, ')'){% endif %} AS `title`");
		$query->from("`#__{{ entity.storage.table }}`");
{# endcontext #}

		$query->where('`id`=' . (int) $this->value);
		$db->setQuery($query);

		$title = $db->loadResult();
		if (empty($title))
		{
			$title = JText::_('COM_{{ project.name | constant }}_{{ entity.name | constant }}_SELECT');
		}
		$title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');

		// The current user display field.
		$html[] = '<div class="fltlft">';
		$html[] = '  <input type="text" id="' . $this->id . '_name" value="' . $title . '" disabled="disabled" size="35" />';
		$html[] = '</div>';

		// The user select button.
		$a_title = JText::_('COM_{{ project.name | constant }}_{{ entity.name | constant }}_SELECT');
		$a_href  = $link . '&amp;' . JSession::getFormToken() . '=1';
		$html[]  = '<div class="button2-left">';
		$html[]  = '  <div class="blank">';
		$html[]  = '    <a class="modal" title="' . $a_title . '" ' . ' href="' . $a_href . '" rel="{handler: \'iframe\', size: {x: 800, y: 450}}">'
			. JText::_('COM_{{ project.name | constant }}_{{ entity.name | constant }}_SELECT_BUTTON')
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
