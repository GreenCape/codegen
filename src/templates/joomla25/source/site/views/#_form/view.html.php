<?template scope="entity"?>
{% if entity.role != 'lookup' and entity.role != 'map' %}
<?php
/**
 * Default {{ entity.name | class }} Form View
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
 * {{ project.title }} HTML Edit View for {{ entity.name | class }}
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}
 * @since      1.0.0
{# endalign #}
 */
class {{ project.name | class }}View{{ entity.name | class }}_Form extends JViewLegacy
{
	/**
	 * The form
	 * @var  JForm
	 */
	protected $form;

	/**
	 * The current item
	 * @var  JObject
	 */
	protected $item;

	/**
	 * The return address
	 * @var  string
	 */
	protected $return_page;

	/**
	 * The current state
	 * @var  JObject
	 */
	protected $state;
{# override properties #}{# endoverride #}
{# override display #}

	/**
	 * Display the view
	 *
{# align("\t", '  ') #}
	 * @param	string	$tpl	  Optional template to use
	 *
	 * @return  mixed  False on error, null otherwise.
{# endalign #}
	 */
	public function display($tpl = null)
	{
		$app  = JFactory::getApplication();
		$user = JFactory::getUser();

		$this->state       = $this->get('State');
		$this->item        = $this->get('Item');
		$this->form        = $this->get('Form');
		$this->return_page = $this->get('ReturnPage');

		if (empty($this->item->id))
		{
			$authorised = $user->authorise('core.create', 'com_{{ project.name | file }}'){% if entity.special.category %}|| count($user->getAuthorisedCategories('com_{{ project.name | file }}', 'core.create')){% endif %};
		}
		else
		{
			$authorised = $this->item->params->get('access-edit');
		}

		if ($authorised !== true)
		{
			$app->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'error');
			return false;
		}

		if (count($errors = $this->get('Errors')))
		{
			$app->enqueueMessage(implode("\n", $errors), 'error');
			return false;
		}

		$params = $this->state->params;

		// Escape strings for HTML output
		$this->pageclass_sfx = htmlspecialchars($params->get('pageclass_sfx'));

		$this->params = $params;
		$this->user   = $user;
{% if entity.special.category %}

		if ($this->params->get('enable_category') == 1)
		{
			$this->form->setFieldAttribute('{{ entity.special.category.name }}', 'default',  $params->get('{{ entity.special.category.name }}', 1));
		}
{% endif %}

		$this->_prepareDocument();

		return parent::display($tpl);
	}
{# endoverride #}
{# override _prepareDocument #}

	/**
	 * Prepare the document
	 *
{# align("\t", '  ') #}
	 * @return  void
{# endalign #}
	 */
	protected function _prepareDocument()
	{
		{{ project.name | class }}Helper::prepareDocument($this->document, $this->params, JText::_('COM_{{ project.name | constant }}_{{ entity.name | constant }}_EDIT'));
		return;
	}
{# endoverride #}
{# override methods #}{# endoverride #}
}
{% endif %}
