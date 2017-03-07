<?template scope="entity"?>
{% if entity.role != 'lookup' and entity.role != 'map' %}
<?php
/**
 * {{ project.title }} HTML View for {{ entity.name | plural | class }}
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
 * {{ project.title }} HTML View for {{ entity.name | plural | class }}
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}
 * @since      1.0.0
{# endalign #}

 */
class {{ project.name | class }}View{{ entity.name | plural | class }} extends JViewLegacy
{
	/**
	 * The items
	 * @var  object[]
	 */
	protected $items;

	/**
	 * The pagination object
	 * @var  JPagination
	 */
	protected $pagination;

	/**
	 * The current state
	 * @var  JObject
	 */
	protected $state;

	/**
	 * The view parameters
	 * @var  JRegistry
	 */
	protected $params;
{# override properties #}{# endoverride #}
{# override display #}

	/**
	 * Display the view
	 *
{# align("\t", '  ') #}
	 * @param	string	$tpl	  Template
	 *
	 * @return  void
{# endalign #}
	 */
	public function display($tpl = null)
	{
		$app              = JFactory::getApplication();
		$this->state      = $this->get('State');
		$this->items      = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->params     = $app->getParams('com_{{ project.name | file }}');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

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
		{{ project.name | class }}Helper::prepareDocument($this->document, $this->params, JText::_('COM_{{ project.name | constant }}_{{ entity.name | constant }}_EDIT'));
		return;
	}
{# endoverride #}
{# override methods #}{# endoverride #}
}
{% endif %}
