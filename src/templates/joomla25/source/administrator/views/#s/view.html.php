<?template scope="entity"?>
{% if entity.special.key %}
<?php
/**
 * {{ project.title }} {{ entity.name | title }} Admin HTML List View
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
 * {{ entity.name | title }} Admin HTML List View
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}
 * @since      1.0.0
{# endalign #}
 */
class {{ project.name | class }}AdminView{{ entity.name | plural | class }} extends JView
{
{% if entity.special.category and entity.special.ordering %}
	/**
	 * The categories
	 * @var  object[]
	 */
	public $categories;

{% endif %}
	/**
	 * The items
	 * @var  object[]
	 */
	public $items;

	/**
	 * The pagination object
	 * @var  JPagination
	 */
	public $pagination;

	/**
	 * The current state
	 * @var  JObject
	 */
	public $state;
{# override properties #}{# endoverride #}
{# override display #}

	/**
	 * Display the view
	 *
{# align("\t", '  ') #}
	 * @param	string	$tpl	  (opt.) Template to use
	 *
	 * @throws  Exception
	 * @return  mixed
{# endalign #}
	 */
	public function display($tpl=null)
	{
{% for property in entity.references.foreignKeys  %}
{% set foreignEntity = project.entities[property.type.entity] %}
{% if property.role != 'category' %}
		require_once JPATH_COMPONENT . '/models/fields/{{ foreignEntity.name | singular | file }}.php';

{% endif %}
{% endfor %}
{% if entity.special.category and entity.special.ordering %}
		$this->categories = $this->get('CategoryOrders');
{% endif %}}
		$this->items      = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->state      = $this->get('State');

		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);
		}

		// We don't need toolbar in the modal window.
		if ($this->getLayout() !== 'modal')
		{
			$this->addToolbar();
		}

		return parent::display($tpl);
	}
{# endoverride #}
{# override addToolbar #}

	/**
	 * Add the page title and toolbar
	 *
{# align("\t", '  ') #}
	 * @return  void
{# endalign #}
	 */
	protected function addToolbar()
	{
		require_once JPATH_COMPONENT . '/helpers/{{ project.name | file }}.php';

		$canDo = {{ project.name | class }}AdminHelper::getActions({% if entity.special.category %}$this->state->get('filter.category_id'){% endif %});
		$user  = JFactory::getUser();

		JToolBarHelper::title(JText::_('COM_{{ project.name | constant }}_{{ entity.name | constant }}_LIST'), '{{ entity.name | singular | file }}.png');

		if ($canDo->get('core.create') || (count($user->getAuthorisedCategories('com_content', 'core.create'))) > 0 )
		{
			JToolBarHelper::addNew('{{ entity.name | singular | file }}.add');
		}

		if (($canDo->get('core.edit')) || ($canDo->get('core.edit.own')))
		{
			JToolBarHelper::editList('{{ entity.name | singular | file }}.edit');
		}
{% if entity.special.published or entity.special.featured or entity.special.checked_out %}

		if ($canDo->get('core.edit.state'))
		{
{% if entity.special.published or entity.special.featured %}
			JToolBarHelper::divider();

{% if entity.special.published %}
			JToolBarHelper::publish('{{ entity.name | plural | file }}.publish', 'JTOOLBAR_PUBLISH', true);
			JToolBarHelper::unpublish('{{ entity.name | plural | file }}.unpublish', 'JTOOLBAR_UNPUBLISH', true);
{% endif %}
{% if entity.special.featured %}
			JToolBarHelper::custom('{{ entity.name | plural | file }}.featured', 'featured.png', 'featured_f2.png', 'JFEATURED', true);
{% endif %}
{% endif %}
{% if entity.special.published or entity.special.checked_out %}

			JToolBarHelper::divider();

{% if entity.special.published %}
			JToolBarHelper::archiveList('{{ entity.name | plural | file }}.archive');
{% endif %}
{% if entity.special.checked_out %}
			JToolBarHelper::checkin('{{ entity.name | plural | file }}.checkin');
{% endif %}
{% endif %}
		}
{% endif %}
{% if entity.special.published %}

		if ($this->state->get('filter.state') == -2 && $canDo->get('core.delete'))
		{
			JToolBarHelper::deleteList('', '{{ entity.name | plural | file }}.delete', 'JTOOLBAR_EMPTY_TRASH');
			JToolBarHelper::divider();
		}
		elseif ($canDo->get('core.edit.state'))
		{
			JToolBarHelper::trash('{{ entity.name | plural | file }}.trash', 'JTOOLBAR_TRASH');
			JToolBarHelper::divider();
		}
{% endif %}
{% if not entity.special.published %}

		if ($canDo->get('core.delete'))
		{
			JToolBarHelper::deleteList('', '{{ entity.name | plural | file }}.delete', 'JTOOLBAR_DELETE');
			JToolBarHelper::divider();
		}
{% endif %}

		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::preferences('com_{{ project.name | file }}');
			JToolBarHelper::divider();
		}
		return;
	}
{# endoverride #}
{# override toggle #}

	/**
	 * Create a clickable icon to change the state of an item
	 *
{# align("\t", '  ') #}
	 * @param	mixed	$value	  The current value
	 * @param	integer	$i	  The index
	 * @param	array	$prefix	  The common prefix for the tasks
	 * @param	array	$task	  The list of tasks indexed by value. Set null for default ['on', 'off']
	 * @param	array	$img	  The icons indexed by value. Set null for default []
	 * @param	array	$alt	  The alt texts for the icons indexed by value. Set null for default []
	 * @param	array	$action	  The tooltips indexed by value. Set null for default (none)
	 *
	 * @return  string
{# endalign #}
	 */
	public function toggle($value, $i, $prefix, $task = null, $img = null, $alt = null, $action = null)
	{
		if (empty($task))
		{
			$task = array('on', 'off');
		}
		if (empty($img))
		{
			$img = array('tick.png', 'publish_x.png');
		}
		if (empty($alt))
		{
			$alt = array(
				JText::_('COM_{{ project.name | constant }}_ENABLED'),
				JText::_('COM_{{ project.name | constant }}_DISABLED')
			);
		}
		if (empty($action))
		{
			$action = array('', '');
		}

		$js   = "return listItemTask('cb{$i}', '{$prefix}{$task[$value]}');";
		$icon = JHtml::_('image', 'admin/' . $img[$value], $alt[$value], null, true);
		$href = '<a href="#" onclick="' . $js . '" title="' . $action[$value] . '">' . $icon . '</a>';

		return $href;
	}
{# endoverride #}
{# override methods #}{# endoverride #}
}
{% endif %}
