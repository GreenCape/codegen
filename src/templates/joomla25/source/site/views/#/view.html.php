<?template scope="entity"?>
{% if entity.role != 'lookup' and entity.role != 'map' %}
<?php
/**
 * {{ project.title }} HTML View for {{ entity.name | class }}
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
 * {{ project.title }} HTML View for {{ entity.name | class }}
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}
 * @since      1.0.0
{# endalign #}
 */
class {{ project.name | class }}View{{ entity.name | class }} extends JViewLegacy
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
	protected $item;

	/**
	 * The view parameters
	 * @var  JRegistry
	 */
	protected $params;
{# override properties #}{# endoverride #}
{# override display #}

	/**
	 * Display an entry
	 *
{# align("\t", '  ') #}
	 * @param	string	$tpl	  Template
	 *
	 * @return  void
{# endalign #}
	 */
	public function display($tpl=null)
	{
		$app          = JFactory::getApplication();
		$this->state  = $this->get('State');
		$this->item   = $this->get('Item');
		$this->params = $app->getParams('com_{{ project.name | file }}');
		$this->print  = $app->input->getInt('print', 0);

		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$offset = $this->state->get('list.offset');

		JPluginHelper::importPlugin('content');
		$dispatcher  = JDispatcher::getInstance();
		$dispatcher->trigger('onContentPrepare', array ('com_{{ project.name | file }}.{{ entity.name | singular | file }}', &$this->item, &$this->params, $offset));

		$this->item->event = new stdClass;
		$results = $dispatcher->trigger('onContentAfterTitle', array('com_{{ project.name | file }}.{{ entity.name | singular | file }}', &$this->item, &$this->params, $offset));
		$this->item->event->afterDisplayTitle = trim(implode("\n", $results));

		$results = $dispatcher->trigger('onContentBeforeDisplay', array('com_{{ project.name | file }}.{{ entity.name | singular | file }}', &$this->item, &$this->params, $offset));
		$this->item->event->beforeDisplayContent = trim(implode("\n", $results));

		$results = $dispatcher->trigger('onContentAfterDisplay', array('com_{{ project.name | file }}.{{ entity.name | singular | file }}', &$this->item, &$this->params, $offset));
		$this->item->event->afterDisplayContent = trim(implode("\n", $results));

		$this->_prepareDocument();

		parent::display($tpl);
		return;
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
		{{ project.name | class }}Helper::prepareDocument($this->document, $this->params, JText::_('COM_{{ project.name | constant }}_{{ entity.name | constant }}_ITEM'));

		return;
	}
{# endoverride #}
{# override methods #}{# endoverride #}
}
{% endif %}
