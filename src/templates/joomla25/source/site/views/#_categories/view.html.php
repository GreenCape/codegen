<?template scope="entity"?>
{% if entity.role != 'lookup' and entity.role != 'map' and entity.special.category %}
<?php
/**
 * Default {{ entity.name | class }} Categories HTML View
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
 * Content categories view.
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}
 * @since      1.0.0
{# endalign #}
 */
class {{ project.name | class }}View{{ entity.name | class }}_Categories extends JViewLegacy
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
	protected $item  = null;

	/**
	 * The items
	 * @var  object[]
	 */
	protected $items = null;
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
		$app    = JFactory::getApplication();
		$state  = $this->get('State');
		$items  = $this->get('Items');
		$parent = $this->get('Parent');

		if (count($errors = $this->get('Errors')))
		{
			$app->enqueueMessage(implode("\n", $errors), 'error');
			return false;
		}

		if ($items === false)
		{
			$app->enqueueMessage(JText::_('COM_[[appname:constant/]]_ERROR_CATEGORY_NOT_FOUND'), 'warning');
			return false;
		}

		if ($parent == false)
		{
			$app->enqueueMessage(JText::_('COM_[[appname:constant/]]_ERROR_PARENT_CATEGORY_NOT_FOUND'), 'warning');
			return false;
		}

		$params = $state->params;
		$items  = array($parent->id => $items);

		$this->pageclass_sfx = htmlspecialchars($params->get('pageclass_sfx'));

		$this->{{ entity.name | singular | file }}_maxLevelcat = $params->get('{{ entity.name | singular | file }}_maxLevelcat', -1);
		$this->assignRef('params', $params);
		$this->assignRef('parent', $parent);
		$this->assignRef('items',  $items);

		$this->_prepareDocument();

		parent::display($tpl);
		return null;
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
		{{ project.name | class }}Helper::prepareDocument($this->document, $this->params, JText::_('COM_{{ project.name | constant }}_{{ entity.name | constant }}_CATEGORIES'));
		return;
	}
{# endoverride #}
{# override methods #}{# endoverride #}
}
{% endif %}}
