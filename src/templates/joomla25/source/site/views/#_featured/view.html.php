<?template scope="entity"?>
{% if entity.role != 'lookup' and entity.role != 'map' and entity.special.featured %}
<?php
/**
 * Default {{ entity.name | class }} Featured HTML View
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

/** @TODO  Adapt this file */

/**
 * Frontpage View class
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}
 * @since      1.0.0
{# endalign #}
 */
class {{ project.name | class }}View{{ entity.name | class }}_Featured extends JViewLegacy
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

	/**
	 * The lead items
	 * @var  array
	 */
	protected $lead_items = array();

	/**
	 * The intro items
	 * @var  array
	 */
	protected $intro_items = array();

	/**
	 * The link items
	 * @var  array
	 */
	protected $link_items = array();

	/**
	 * The number of columns
	 * @var  int
	 */
	protected $columns = 1;
{# override properties #}{# endoverride #}
{# override display #}

	/**
	 * Display the view
	 *
{# align("\t", '  ') #}
	 * @return  mixed  False on error, null otherwise.
{# endalign #}
	 */
	function display($tpl = null)
	{
		// Initialise variables.
		$user = JFactory::getUser();
		$app = JFactory::getApplication();

		$state      = $this->get('State');
		$items      = $this->get('Items');
		$pagination = $this->get('Pagination');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseWarning(500, implode("\n", $errors));
			return false;
		}

		$params = &$state->params;

		// PREPARE THE DATA

		// Get the metrics for the structural page layout.
		$numLeading = $params->def('num_leading_items', 1);
		$numIntro = $params->def('num_intro_items', 4);
		$numLinks = $params->def('num_links', 4);

		// Compute the {{ entity.name | singular | title }} slugs and prepare introtext (runs content plugins).
		foreach ($items as $i => & $item)
		{
			$item->slug = $item->alias ? ($item->id . ':' . $item->alias) : $item->id;
			$item->catslug = ($item->category_alias) ? ($item->catid . ':' . $item->category_alias) : $item->catid;
			$item->parent_slug = ($item->parent_alias) ? ($item->parent_id . ':' . $item->parent_alias) : $item->parent_id;
			// No link for ROOT category
			if ($item->parent_alias == 'root') {
				$item->parent_slug = null;
			}

			$item->event = new stdClass();

			$dispatcher = JDispatcher::getInstance();

			$item->introtext = JHtml::_('content.prepare', $item->introtext, '', 'com_{{ project.name | file }}.featured');

			$results = $dispatcher->trigger('onContentAfterTitle', array('com_{{ project.name | file }}.{{ entity.name | singular | file }}', &$item, &$item->params, 0));
			$item->event->afterDisplayTitle = trim(implode("\n", $results));

			$results = $dispatcher->trigger('onContentBeforeDisplay', array('com_{{ project.name | file }}.{{ entity.name | singular | file }}', &$item, &$item->params, 0));
			$item->event->beforeDisplayContent = trim(implode("\n", $results));

			$results = $dispatcher->trigger('onContentAfterDisplay', array('com_{{ project.name | file }}.{{ entity.name | singular | file }}', &$item, &$item->params, 0));
			$item->event->afterDisplayContent = trim(implode("\n", $results));
		}

		// Preprocess the breakdown of leading, intro and linked {{ entity.name | plural | title }}.
		// This makes it much easier for the designer to just interogate the arrays.
		$max = count($items);

		// The first group is the leading {{ entity.name | plural | title }}.
		$limit = $numLeading;
		for ($i = 0; $i < $limit && $i < $max; $i++)
		{
			$this->lead_items[$i] = &$items[$i];
		}

		// The second group is the intro {{ entity.name | plural | title }}.
		$limit = $numLeading + $numIntro;
		// Order {{ entity.name | plural | title }} across, then down (or single column mode)
		for ($i = $numLeading; $i < $limit && $i < $max; $i++)
		{
			$this->intro_items[$i] = &$items[$i];
		}

		$this->columns = max(1, $params->def('num_columns', 1));
		$order = $params->def('multi_column_order', 1);

		if ($order == 0 && $this->columns > 1)
		{
			// call order down helper
			$this->intro_items = {{ project.name | class }}HelperQuery::orderDownColumns($this->intro_items, $this->columns);
		}

		// The remainder are the links.
		for ($i = $numLeading + $numIntro; $i < $max; $i++)
		{
			$this->link_items[$i] = &$items[$i];
		}

		//Escape strings for HTML output
		$this->pageclass_sfx = htmlspecialchars($params->get('pageclass_sfx'));

		$this->assignRef('params', $params);
		$this->assignRef('items', $items);
		$this->assignRef('pagination', $pagination);
		$this->assignRef('user', $user);

		$this->_prepareDocument();

		parent::display($tpl);
		return;
	}
{# endoverride #}
{# override _prepareDocument #}

	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{
		{{ project.name | class }}Helper::prepareDocument($this->document, $this->params, JText::_('COM_{{ project.name | constant }}_{{ entity.name | constant }}_CATEGORIES'));

		// Add feed links
		if ($this->params->get('show_feed_link', 1))
		{
			$link = '&format=feed&limitstart=';
			$attribs = array('type' => 'application/rss+xml', 'title' => 'RSS 2.0');
			$this->document->addHeadLink(JRoute::_($link . '&type=rss'), 'alternate', 'rel', $attribs);
			$attribs = array('type' => 'application/atom+xml', 'title' => 'Atom 1.0');
			$this->document->addHeadLink(JRoute::_($link . '&type=atom'), 'alternate', 'rel', $attribs);
		}
		return;
	}
{# endoverride #}
{# override methods #}{# endoverride #}
}
{% endif %}
