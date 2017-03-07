<?template scope="entity"?>
{% if entity.special.ordering and entity.special.key %}
<?php
/**
 * {{ project.title }} {{ entity.name | title }} Ordering Form Field
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
 * {{ entity.name | title }} Ordering Form Field
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}
 * @since      1.0.0
{# endalign #}

 */
class JFormField{{ entity.name | class }}Ordering extends JFormField
{
	/**
	 * The form field type
	 * @var  string
	 */
	protected $type = '{{ entity.name | class }}Ordering';
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
		$html = array();
		$attr = '';

		// Initialize some field attributes.
		$attr .= $this->element['class'] ? ' class="' . (string)$this->element['class'] . '"' : '';
		$attr .= ((string)$this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
		$attr .= $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';

		// Initialize JavaScript field attributes.
		$attr .= $this->element['onchange'] ? ' onchange="' . (string)$this->element['onchange'] . '"' : '';

		// Get some field values from the form.
		${{ entity.name | variable }}Id = (int) $this->form->getValue('{{ entity.special.key.name }}');
{% if entity.special.category %}

		$categoryId  = (int) $this->form->getValue('{{ entity.special.category.name }}');
{% endif %}

		// Build the query for the ordering list.
		$query = "SELECT {{ entity.special.ordering.name }} AS value, {% if entity.special.title %}{{ entity.special.title.name }}{% elseif entity.dynName %}CONCAT_WS(' '{% for property in entity.dynName %}, {{ property.name | variable }}{% endfor %}){% endif %} AS text"
		. "\n FROM #__{{ entity.storage.table }}"
{% if entity.special.category %}
		. "\n WHERE {{ entity.special.category.name }}=" . (int) $categoryId
{% endif %}
		. "\n ORDER BY {{ entity.special.ordering.name }}"
		;

		if ((string)$this->element['readonly'] == 'true')
		{
			// Create a read-only list (no name) with a hidden input to store the value
			$html[] = JHtml::_('list.ordering', '', $query, trim($attr), $this->value, ${{ entity.name | variable }}Id ? 0 : 1);
			$html[] = '<input type="hidden" name="'.$this->name.'" value="'.$this->value.'"/>';
		}
		else
		{
			// Create a regular list
			$html[] = JHtml::_('list.ordering', $this->name, $query, trim($attr), $this->value, ${{ entity.name | variable }}Id ? 0 : 1);
		}
		return implode($html);
	}
{# endoverride #}
{# override methods #}{# endoverride #}
}
{% endif %}
