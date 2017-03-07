<?template scope="entity"?>
{% if entity.role != 'lookup' and entity.role != 'map' and entity.special.category %}
<?php
/**
 * Default {{ entity.name | class }} Category HTML View
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
 * HTML View class for the Content component
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}
 * @since      1.0.0
{# endalign #}
 */
class {{ project.name | class }}View{{ entity.name | class }}_Category extends JViewLegacy
{
	/**
	 * The current state
	 * @var  JObject
	 */
	protected $state;

	/**
	 * The items
	 * @var  object[]
	 */
	protected $items;

	/**
	 * The category
	 * @var  object
	 */
	protected $category;

	/**
	 * The sub-categories
	 * @var  object[]
	 */
	protected $children;

	/**
	 * The pagination object
	 * @var  JPagination
	 */
	protected $pagination;

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
	 * @param	string	$tpl	  Optional template to use
	 *
	 * @return  mixed  False on error, null otherwise.
{# endalign #}
	 */
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();
		$user = JFactory::getUser();

		// Get some data from the models
		$state      = $this->get('State');
		$params     = $state->params;
		$items      = $this->get('Items');
		$category   = $this->get('Category');
		$children   = $this->get('Children');
		$parent     = $this->get('Parent');
		$pagination = $this->get('Pagination');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		if ($category == false)
		{
			return JError::raiseError(404, JText::_('JGLOBAL_CATEGORY_NOT_FOUND'));
		}

		if ($parent == false)
		{
			return JError::raiseError(404, JText::_('JGLOBAL_CATEGORY_NOT_FOUND'));
		}

		// Setup the category parameters.
		$cparams = $category->getParams();
		$category->params = clone($params);
		$category->params->merge($cparams);

		// Check whether category access level allows access.
		$user = JFactory::getUser();
		$groups = $user->getAuthorisedViewLevels();
		if (!in_array($category->access, $groups))
		{
			return JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
		}

		/*
		 * Prepare the data
		 * Get the metrics for the structural page layout.
		 */
		$numLeading = $params->def('num_leading_items', 1);
		$numIntro   = $params->def('num_intro_items', 4);
		$numLinks   = $params->def('num_links', 4);

		// Compute the {{ entity.name | singular | title }} slugs and prepare introtext (runs content plugins).
		for ($i = 0, $n = count($items); $i < $n; $i++)
		{
			$item = &$items[$i];
			$item->slug = $item->alias ? ($item->id . ':' . $item->alias) : $item->id;

			// No link for ROOT category
			if ($item->parent_alias == 'root')
			{
				$item->parent_slug = null;
			}

			$item->event = new stdClass;

			$dispatcher = JDispatcher::getInstance();

			$item->introtext = JHtml::_('content.prepare', $item->introtext, '', 'com_{{ project.name | file }}.category');

			$results = $dispatcher->trigger('onContentAfterTitle', array('com_{{ project.name | file }}.{{ entity.name | singular | file }}', &$item, &$item->params, 0));
			$item->event->afterDisplayTitle = trim(implode("\n", $results));

			$results = $dispatcher->trigger('onContentBeforeDisplay', array('com_{{ project.name | file }}.{{ entity.name | singular | file }}', &$item, &$item->params, 0));
			$item->event->beforeDisplayContent = trim(implode("\n", $results));

			$results = $dispatcher->trigger('onContentAfterDisplay', array('com_{{ project.name | file }}.{{ entity.name | singular | file }}', &$item, &$item->params, 0));
			$item->event->afterDisplayContent = trim(implode("\n", $results));
		}

		/*
		 * Check for layout override only if this is not the active menu item
		 * If it is the active menu item, then the view and category id will match
		 */
		$active = $app->getMenu()->getActive();
		if ((!$active) || ((strpos($active->link, 'view=category') === false) || (strpos($active->link, '&id=' . (string) $category->id) === false)))
		{
			// Get the layout from the merged category params
			if ($layout = $category->params->get('category_layout'))
			{
				$this->setLayout($layout);
			}
		}
		elseif (isset($active->query['layout']))
		{
			/*
			 * At this point, we are in a menu item, so we don't override the layout
			 * We need to set the layout from the query in case this is an alternative menu item (with an alternative layout)
			 */
			$this->setLayout($active->query['layout']);
		}

		/*
		 * For blog layouts, preprocess the breakdown of leading, intro and linked {{ entity.name | plural | title }}.
		 * This makes it much easier for the designer to just interrogate the arrays.
		 */
		if (($params->get('layout_type') == 'blog') || ($this->getLayout() == 'blog'))
		{
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
				// Call order down helper
				$this->intro_items = {{ project.name | class }}HelperQuery::orderDownColumns($this->intro_items, $this->columns);
			}

			$limit = $numLeading + $numIntro + $numLinks;

			// The remainder are the links.
			for ($i = $numLeading + $numIntro; $i < $limit && $i < $max;$i++)
			{
					$this->link_items[$i] = &$items[$i];
			}
		}

		$children = array($category->id => $children);

		// Escape strings for HTML output
		$this->pageclass_sfx = htmlspecialchars($params->get('pageclass_sfx'));

		$this->maxLevel   = $params->get('{{ entity.name | singular | file }}_maxLevel', -1);
		$this->state      = $state;
		$this->items      = $items;
		$this->category   = $category;
		$this->children   = $children;
		$this->params     = $params;
		$this->parent     = $parent;
		$this->pagination = $pagination;
		$this->user       = $user;

		$this->_prepareDocument();

		parent::display($tpl);
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
		$app     = JFactory::getApplication();
		$menus     = $app->getMenu();
		$pathway = $app->getPathway();

		{{ project.name | class }}Helper::prepareDocument($this->document, $this->params, JText::_('COM_{{ project.name | constant }}_{{ entity.name | constant }}_ITEMS'));

		// Because the application sets a default page title, we need to get it from the menu item itself
		$menu = $menus->getActive();

		if ($menu && ($menu->query['option'] != 'com_{{ project.name | file }}' || $menu->query['view'] == '{{ entity.name | singular | file }}' || $id != $this->category->id))
		{
			$id = (int) $menu->query['id'];
			$path = array(array('title' => $this->category->title, 'link' => ''));
			$category = $this->category->getParent();

			while (($menu->query['option'] != 'com_{{ project.name | file }}' || $menu->query['view'] == '{{ entity.name | singular | file }}' || $id != $category->id) && $category->id > 1)
			{
				$path[] = array('title' => $category->title, 'link' => {{ project.name | class }}HelperRoute::get{{ entity.name | class }}CategoryRoute($category->id));
				$category = $category->getParent();
			}

			$path = array_reverse($path);

			foreach ($path as $item)
			{
				$pathway->addItem($item['title'], $item['link']);
			}
		}

		if ($this->category->metadesc)
		{
			$this->document->setDescription($this->category->metadesc);
		}
		elseif (!$this->category->metadesc && $this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->category->metakey)
		{
			$this->document->setMetadata('keywords', $this->category->metakey);
		}
		elseif (!$this->category->metakey && $this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}

		if ($app->getCfg('MetaAuthor') == '1')
		{
			$this->document->setMetaData('author', $this->category->getMetadata()->get('author'));
		}

		$mdata = $this->category->getMetadata()->toArray();

		foreach ($mdata as $k => $v)
		{
			if ($v)
			{
				$this->document->setMetadata($k, $v);
			}
		}

		// Add feed links
		if ($this->params->get('show_feed_link', 1))
		{
			$link = '&format=feed&limitstart=';
			$attribs = array('type' => 'application/rss+xml', 'title' => 'RSS 2.0');
			$this->document->addHeadLink(JRoute::_($link . '&type=rss'), 'alternate', 'rel', $attribs);
			$attribs = array('type' => 'application/atom+xml', 'title' => 'Atom 1.0');
			$this->document->addHeadLink(JRoute::_($link . '&type=atom'), 'alternate', 'rel', $attribs);
		}
	}
{# endoverride #}
{# override methods #}{# endoverride #}
}
{% endif %}
