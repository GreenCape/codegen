<?template scope="entity"?>
{% if entity.special.key %}
<?php
/**
 * {{ project.title }} {{ entity.name | title }} Admin HTML Item View
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
 * {{ entity.name | title }} HTML Item View
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}
 * @since      1.0.0
{# endalign #}
 */
class {{ project.name | class }}AdminView{{ entity.name | class }} extends JView
{
	/**
	 * The current state
	 * @var  JObject
	 */
	protected $state;

	/**
	 * The current item
	 * @var  JObject
	 */
	public $item;

	/**
	 * The form
	 * @var  JForm
	 */
	public $form;
{# override properties #}{# endoverride #}
{# override display #}

	/**
	 * Display the view
	 *
{# align("\t", '  ') #}
	 * @param	string	$tpl	  (opt.) Template to use
	 *
	 * @throws  Exception on errors
	 * @return  mixed
{# endalign #}
	 */
	public function display($tpl=null)
	{
		$this->form  = $this->get('Form');
		$this->item  = $this->get('Item');
		$this->state = $this->get('State');

		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);
		}

		$this->addToolbar();
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

		$input = JFactory::getApplication()->input;
		$input->set('hidemainmenu', true);

		$user       = JFactory::getUser();
{% if entity.special.checked_out or entity.special.created_by %}
		$userId     = $user->get('id');
{% endif %}
		$isNew      = intval($this->item->get('{{ entity.special.key.name }}')) == 0;
{% if entity.special.checked_out %}
		$checkedOut = !($this->item->checked_out == 0 || $this->item->checked_out == $userId);
{% endif %}
		$canDo      = {{ project.name | class }}AdminHelper::getActions({% if entity.special.category %}$this->state->get('filter.category_id'), {% endif %}$this->item->get('{{ entity.special.key.name }}'));

		JToolBarHelper::title($isNew ? JText::_('COM_{{ project.name | constant }}_{{ entity.name | constant }}_NEW') : JText::_('COM_{{ project.name | constant }}_{{ entity.name | constant }}_EDIT'), '{{ entity.name | singular | file }}.png');

		// For new records, check the create permission.
		if ($isNew && (count($user->getAuthorisedCategories('com_{{ project.name | file }}', 'core.create')) > 0))
		{
			JToolBarHelper::apply('{{ entity.name | singular | file }}.apply');
			JToolBarHelper::save('{{ entity.name | singular | file }}.save');
			JToolBarHelper::save2new('{{ entity.name | singular | file }}.save2new');
			JToolBarHelper::cancel('{{ entity.name | singular | file }}.cancel');
		}
{% if entity.special.checked_out %}
		else
		{
			// Can't save the record if it's checked out.
			if (!$checkedOut)
			{
				// Since it's an existing record, check the edit permission{% if entity.special.created_by %}, or fall back to edit own if the owner{% endif %}
.				if ($canDo->get('core.edit'){% if entity.special.created_by %}|| ($canDo->get('core.edit.own') && $this->item->created_by == $userId){% endif %})
				{
					JToolBarHelper::apply('{{ entity.name | singular | file }}.apply');
					JToolBarHelper::save('{{ entity.name | singular | file }}.save');

					// We can save this record, but check the create permission to see if we can return to make a new one.
					if ($canDo->get('core.create'))
					{
						JToolBarHelper::save2new('{{ entity.name | singular | file }}.save2new');
					}
				}
			}

			// If checked out, we can still save
			if ($canDo->get('core.create'))
			{
				JToolBarHelper::save2copy('{{ entity.name | singular | file }}.save2copy');
			}

			JToolBarHelper::cancel('{{ entity.name | singular | file }}.cancel', 'JTOOLBAR_CLOSE');
		}
{% endif %}
{% if not entity.special.checked_out %}
		else
		{
			// Since it's an existing record, check the edit permission{% if entity.special.created_by %}, or fall back to edit own if the owner{% endif %}.
			if ($canDo->get('core.edit'){% if entity.special.created_by %}|| ($canDo->get('core.edit.own') && $this->item->created_by == $userId){% endif %})
			{
				JToolBarHelper::apply('{{ entity.name | singular | file }}.apply');
				JToolBarHelper::save('{{ entity.name | singular | file }}.save');

				// We can save this record, but check the create permission to see if we can return to make a new one.
				if ($canDo->get('core.create'))
				{
					JToolBarHelper::save2new('{{ entity.name | singular | file }}.save2new');
				}
			}

			if ($canDo->get('core.create'))
			{
				JToolBarHelper::save2copy('{{ entity.name | singular | file }}.save2copy');
			}

			JToolBarHelper::cancel('{{ entity.name | singular | file }}.cancel', 'JTOOLBAR_CLOSE');
		}
{% endif %}

		JToolBarHelper::divider();
		JToolBarHelper::help('COM_{{ project.name | constant }}_{{ entity.name | constant }}_EDIT');

		return;
	}
{# endoverride #}
{# override methods #}{# endoverride #}
}
{% endif %}
