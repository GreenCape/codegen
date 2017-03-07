<?template scope="entity"?>
{% if entity.role != 'lookup' and entity.role != 'map' and entity.special.published %}
<?php
/**
 * Default {{ entity.name | class }} Archive View
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

/** @TODO  Adapt this file */

/**
 * HTML View class for the Content component
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}
 * @since      1.0.0
{# endalign #}
 */
class {{ project.name | class }}View{{ entity.name | class }}_Archive extends JViewLegacy
{
	/**
	 * The current state
	 * @var  JObject
	 */
	protected $state = null;

	/**
	 * The current item
	 * @var  JObject
	 */
	protected $item = null;

	/**
	 * The items
	 * @var  object[]
	 */
	protected $items = null;

	/**
	 * The pagination object
	 * @var  JPagination
	 */
	protected $pagination = null;
{# override properties #}{# endoverride #}
{# override display #}

	public function display($tpl = null)
	{
		$app = JFactory::getApplication();
		$user     = JFactory::getUser();

		$state      = $this->get('State');
		$items      = $this->get('Items');
		$pagination = $this->get('Pagination');

		$pathway = $app->getPathway();
		$document = JFactory::getDocument();

		// Get the page/component configuration
		$params = &$state->params;

		foreach ($items as $item)
		{
			$item->catslug = ($item->category_alias) ? ($item->catid . ':' . $item->category_alias) : $item->catid;
			$item->parent_slug = ($item->parent_alias) ? ($item->parent_id . ':' . $item->parent_alias) : $item->parent_id;
		}

		$form = new stdClass();
		// Month Field
		$months = array(
			'' => JText::_('COM_CONTENT_MONTH'),
			'01' => JText::_('JANUARY_SHORT'),
			'02' => JText::_('FEBRUARY_SHORT'),
			'03' => JText::_('MARCH_SHORT'),
			'04' => JText::_('APRIL_SHORT'),
			'05' => JText::_('MAY_SHORT'),
			'06' => JText::_('JUNE_SHORT'),
			'07' => JText::_('JULY_SHORT'),
			'08' => JText::_('AUGUST_SHORT'),
			'09' => JText::_('SEPTEMBER_SHORT'),
			'10' => JText::_('OCTOBER_SHORT'),
			'11' => JText::_('NOVEMBER_SHORT'),
			'12' => JText::_('DECEMBER_SHORT')
		);

		$form->monthField = JHtml::_(
			'select.genericlist',
			$months,
			'month',
			array(
				'list.attr' => 'size="1" class="inputbox"',
				'list.select' => $state->get('filter.month'),
				'option.key' => null
			)
		);

		$years = array();
		$years[] = JHtml::_('select.option', null, JText::_('JYEAR'));
		for ($i = 2000; $i <= 2020; $i++) {
			$years[] = JHtml::_('select.option', $i, $i);
		}
		$form->yearField = JHtml::_(
			'select.genericlist',
			$years,
			'year',
			array('list.attr' => 'size="1" class="inputbox"', 'list.select' => $state->get('filter.year'))
		);
		$form->limitField = $pagination->getLimitBox();

		//Escape strings for HTML output
		$this->pageclass_sfx = htmlspecialchars($params->get('pageclass_sfx'));

		$this->filter = $state->get('list.filter');
		$this->assignRef('form', $form);
		$this->assignRef('items', $items);
		$this->assignRef('params', $params);
		$this->assignRef('user', $user);
		$this->assignRef('pagination', $pagination);

		$this->_prepareDocument();

		parent::display($tpl);
	}
{# endoverride #}
{# override _prepareDocument #}

	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{
		{{ project.name | class }}Helper::prepareDocument($this->document, $this->params, JText::_('COM_{{ project.name | constant }}_{{ entity.name | constant }}_ARCHIVED_ITEMS'));
		return;
	}
{# endoverride #}
{# override methods #}{# endoverride #}
}
{% endif %}}
