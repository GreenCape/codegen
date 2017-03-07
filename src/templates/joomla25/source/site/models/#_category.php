<?template scope="entity"?>
{% if entity.role != 'lookup' and entity.role != 'map' and entity.special.category %}
<?php
/**
 * {{ project.title }} {{ entity.name | title }} Category Controller
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

jimport('joomla.application.component.modellist');

/**
 * {{ entity.name | singular | title }} Category Model
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}
 * @since      1.0.0
{# endalign #}
 */
class {{ project.name | class }}Model{{ entity.name | class }}_Category extends JModelList
{
	/**
	 * current item
	 * @var  array
	 */
	protected $_item = null;

	/**
	 * The items
	 * @var  array
	 */
	protected $_items = null;

	/**
	 * Category siblings
	 * @var  array
	 */
	protected $_siblings = null;

	/**
	 * Sub-categories
	 * @var  array
	 */
	protected $_children = null;

	/**
	 * Parent category
	 * @var  string
	 */
	protected $_parent = null;

	/**
	 * Model context string
	 * @var  string
	 */
	protected $_context = 'com_{{ project.name | file }}.{{ entity.name | singular | file }}.category';

	/**
	 * The category that applies
	 * @var  object
	 */
	protected $_category = null;

	/**
	 * The list of other {{ entity.name | singular | title }} categories
	 * @var  array
	 */
	protected $_categories = null;
{# override properties #}{# endoverride #}
{# override __construct #}

	/**
	 * Constructor
	 *
{# align("\t", '  ') #}
	 * @param	array	$config	  An optional associative array of configuration settings
{# endalign #}
	 *
	 * @see  JController
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
{% if entity.role != 'map' %}
{% if entity.special.title or entity.dynName %}
{% if entity.special.title %}
				'{{ entity.special.title.name }}', '{{ entity.name }}.{{ entity.special.title.name }}',
{% else %}
				'name',
{% endif %}
{% endif %}
{% endif %}
{% for property in entity.listFields %}
{% if property.role == 'key' %}
{% elseif property.role == 'title' %}
{% elseif property.role == 'category' %}
{% else %}
				'{{ property.name | variable }}', '{{ entity.name }}.{{ property.name | variable }}',
{% endif %}
{% endfor %}
{# override extra_fields_filter #}{# endoverride #}
{% for detail in entity.details %}
{% set foreignEntity = project.entities[detail.entity] %}
				'num_{{detail.entity | plural | variable }}_{{detail.reference | variable }}', '{{ foreignEntity.name }}.{{ detail.reference }}',
{% endfor %}
{% if entity.special.featured %}
				'{{ entity.special.featured.name }}', '{{ entity.name }}.{{ entity.special.featured.name }}',
{% endif %}
{% if entity.special.published %}
				'{{ entity.special.published.name }}', '{{ entity.name }}.{{ entity.special.published.name }}',
{% endif %}
{% if entity.special.sticky %}
				'{{ entity.special.sticky.name }}', '{{ entity.name }}.{{ entity.special.sticky.name }}',
{% endif %}
{% for property in entity.references.foreignKeys  %}
{% set foreignEntity = project.entities[property.type.entity] %}
{% if property.role == 'category' %}
{% elseif property.role == 'title' %}
{% else %}
				'{{ property.name | variable }}_name', '{{ foreignEntity.name }}.{{foreignEntity.special.title.name }}',
{% endif %}
{% endfor %}
{% if entity.special.category %}
				'category_title',
{% endif %}
{% if entity.special.ordering %}
				'{{ entity.special.ordering.name }}', '{{ entity.name }}.{{ entity.special.ordering.name }}',
{% endif %}
{% if entity.special.language %}
				'{{ entity.special.language.name }}', '{{ entity.name }}.{{ entity.special.language.name }}',
{% endif %}
				'{{ entity.special.key.name }}', '{{ entity.name }}.{{ entity.special.key.name }}'
			);
		}
		parent::__construct($config);
	}
{# endoverride #}
{# override populateState #}

	/**
	 * Auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
{# align("\t", '  ') #}
	 * @param	string	$ordering	  The ordering column
	 * @param	string	$direction	  The ordering direction
	 *
	 * @return  void
{# endalign #}
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initiliase variables.
		$app = JFactory::getApplication('site');
		$pk     = JRequest::getInt('id');

		$this->setState('category.id', $pk);

		// Load the parameters. Merge Global and Menu Item params into new object
		$params = $app->getParams();
		$menuParams = new JRegistry;

		if ($menu = $app->getMenu()->getActive())
		{
			$menuParams->loadString($menu->params);
		}

		$mergedParams = clone $menuParams;
		$mergedParams->merge($params);

		$this->setState('params', $mergedParams);
		$user  = JFactory::getUser();

		if ((!$user->authorise('core.edit.state', 'com_{{ project.name | file }}')) &&  (!$user->authorise('core.edit', 'com_{{ project.name | file }}')))
		{
			// Limit to published for people who can't edit or edit.state.
			$this->setState('filter.state', 1);
		}
		else
		{
			$this->setState('filter.state', array(0, 1, 2));
		}

		// Process {{ entity.name | singular | file }}_show_noauth parameter
		if (!$params->get('{{ entity.name | singular | file }}_show_noauth'))
		{
			$this->setState('filter.access', true);
		}
		else
		{
			$this->setState('filter.access', false);
		}

		// Optional filter text
		$this->setState('list.filter', JRequest::getString('filter-search'));

		// Filter.order
		$itemid = JRequest::getInt('id', 0) . ':' . JRequest::getInt('Itemid', 0);
		$orderCol = $app->getUserStateFromRequest(
			'com_{{ project.name | file }}.category.list.' . $itemid . '.filter_order',
			'filter_order',
			$ordering,
			'string'
		);

		if (!in_array($orderCol, $this->filter_fields))
		{
			$orderCol = '{{ entity.name }}.{% if entity.special.ordering %}{{ entity.special.ordering.name }}{% else %}{{ entity.special.title.name }}{% endif %}';
		}

		$this->setState('list.ordering', $orderCol);

		$listOrder = $app->getUserStateFromRequest(
			'com_{{ project.name | file }}.category.list.' . $itemid . '.filter_order_Dir',
			'filter_order_Dir',
			$direction,
			'cmd'
		);

		if (!in_array(strtoupper($listOrder), array('ASC', 'DESC', '')))
		{
			$listOrder = 'ASC';
		}

		$this->setState('list.direction', $listOrder);

		$this->setState('list.start', JRequest::getUInt('limitstart', 0));

		// Set limit for query. If list, use parameter. If blog, add blog parameters for limit.
		if ((JRequest::getCmd('layout') == 'blog') || $params->get('layout_type') == 'blog')
		{
			$limit = $params->get('num_leading_items') + $params->get('num_intro_items') + $params->get('num_links');
			$this->setState('list.links', $params->get('num_links'));
		}
		else
		{
			$limit = $app->getUserStateFromRequest('com_{{ project.name | file }}.category.list.' . $itemid . '.limit', 'limit', $params->get('display_num'), 'uint');
		}

		$this->setState('list.limit', $limit);

		// Set the depth of the category query based on parameter
		$showSubcategories = $params->get('show_subcategory_content', '0');

		if ($showSubcategories)
		{
			$this->setState('filter.max_category_levels', $params->get('show_subcategory_content', '1'));
			$this->setState('filter.subcategories', true);
		}
		$this->setState('filter.language', $app->getLanguageFilter());
		$this->setState('layout', JRequest::getCmd('layout'));
	}
{# endoverride #}
{# override getItems #}

	/**
	 * Get the {{ entity.name | plural | title }} in the category
	 *
	 * filter.subcategories indicates whether to include {{ entity.name | plural | title }}
	 * from subcategories in the list or blog.
	 *
{# align("\t", '  ') #}
	 * @return  mixed  An array of {{ entity.name | plural | title }} or false if an error occurs
{# endalign #}
	 */
	public function getItems()
	{
		$limit = $this->getState('list.limit');

		if ($this->_items === null && $category = $this->getCategory())
		{
			$model = JModelLegacy::getInstance('{{ entity.name | plural | class }}', '{{ project.name | class }}Model', array('ignore_request' => true));
			$model->setState('params', JFactory::getApplication()->getParams());
			$model->setState('filter.category_id', $category->id);
			$model->setState('filter.state', $this->getState('filter.state'));
			$model->setState('filter.access', $this->getState('filter.access'));
			$model->setState('filter.language', $this->getState('filter.language'));
			$model->setState('list.ordering', $this->_buildContentOrderBy());
			$model->setState('list.start', $this->getState('list.start'));
			$model->setState('list.limit', $limit);
			$model->setState('list.direction', $this->getState('list.direction'));
			$model->setState('list.filter', $this->getState('list.filter'));
			$model->setState('filter.subcategories', $this->getState('filter.subcategories'));
			$model->setState('filter.max_category_levels', $this->setState('filter.max_category_levels'));
			$model->setState('list.links', $this->getState('list.links'));

			if ($limit >= 0)
			{
				$this->_items = $model->getItems();
				if ($this->_items === false)
				{
					$this->setError($model->getError());
				}
			}
			else
			{
				$this->_items = array();
			}
			$this->_pagination = $model->getPagination();
		}
		return $this->_items;
	}
{# endoverride #}
{# override _buildContentOrderBy #}

	/**
	 * Build the orderby for the query
	 *
{# align("\t", '  ') #}
	 * @return  string  $orderby portion of query
{# endalign #}
	 */
	protected function _buildContentOrderBy()
	{
		$app     = JFactory::getApplication('site');
		$db         = $this->getDbo();
		$params     = $this->state->params;
		$itemid     = JRequest::getInt('id', 0) . ':' . JRequest::getInt('Itemid', 0);
		$orderCol = $app->getUserStateFromRequest('com_{{ project.name | file }}.category.list.' . $itemid . '.filter_order', 'filter_order', '', 'string');
		$orderDirn = $app->getUserStateFromRequest('com_{{ project.name | file }}.category.list.' . $itemid . '.filter_order_Dir', 'filter_order_Dir', '', 'cmd');
		$orderby = ' ';

		if (!in_array($orderCol, $this->filter_fields))
		{
			$orderCol = null;
		}

		if (!in_array(strtoupper($orderDirn), array('ASC', 'DESC', '')))
		{
			$orderDirn = 'ASC';
		}

		if ($orderCol && $orderDirn)
		{
			$orderby .= $db->escape($orderCol) . ' ' . $db->escape($orderDirn) . ', ';
		}

		$itemOrderby     = $params->get('orderby_sec', 'rdate');
		$itemOrderDate   = $params->get('order_date');
		$categoryOrderby = $params->def('orderby_pri', '');
		$secondary       = {{ project.name | class }}HelperQuery::order{{ entity.name | plural | class }}BySecondary($itemOrderby, $itemOrderDate) . ', ';
		$primary         = {{ project.name | class }}HelperQuery::orderbyPrimary($categoryOrderby);

		$orderby .= $db->escape($primary) . ' ' . $db->escape($secondary) . ' {{ entity.name }}.{% if entity.special.created %}{{ entity.special.created.name }}{% else %}{{ entity.special.key.name }}{% endif %} ';

		return $orderby;
	}
{# endoverride #}
{# override getPagination #}

	/**
	 * Get a pagination object
	 *
{# align("\t", '  ') #}
	 * @return  JPagination
{# endalign #}
	 */
	public function getPagination()
	{
		if (empty($this->_pagination))
		{
			return null;
		}
		return $this->_pagination;
	}
{# endoverride #}
{# override getCategory #}

	/**
	 * Get category data for the current category
	 *
{# align("\t", '  ') #}
	 * @return  object
{# endalign #}
	 */
	public function getCategory()
	{
		if (!is_object($this->_item))
		{
			if (isset($this->state->params))
			{
				$params = $this->state->params;
				$options = array();
				$options['countItems'] = $params->get('show_cat_num_items', 1) || !$params->get('{{ entity.name | singular | file }}_{{ entity.name | singular | file }}_show_empty_categories_cat', 0);
			}
			else
			{
				$options['countItems'] = 0;
			}

			$categories = JCategories::getInstance('{{ project.name | class }}.{{ entity.name | class }}', $options);
			$this->_item = $categories->get($this->getState('category.id', 'root'));

			// Compute selected asset permissions.
			if (is_object($this->_item))
			{
				$user = JFactory::getUser();
				$asset = 'com_{{ project.name | file }}.category.' . $this->_item->id;

				// Check general create permission.
				if ($user->authorise('core.create', $asset))
				{
					$this->_item->getParams()->set('access-create', true);
				}

				// TODO: Why aren't we lazy loading the children and siblings?
				$this->_children = $this->_item->getChildren();
				$this->_parent = false;

				if ($this->_item->getParent())
				{
					$this->_parent = $this->_item->getParent();
				}

				$this->_rightsibling = $this->_item->getSibling();
				$this->_leftsibling = $this->_item->getSibling(false);
			}
			else
			{
				$this->_children = false;
				$this->_parent = false;
			}
		}

		return $this->_item;
	}
{# endoverride #}
{# override getParent #}

	/**
	 * Get the parent category
	 *
{# align("\t", '  ') #}
	 * @return  mixed  An array of categories or false if an error occurs
{# endalign #}
	 */
	public function getParent()
	{
		if (!is_object($this->_item))
		{
			$this->getCategory();
		}
		return $this->_parent;
	}
{# endoverride #}
{# override getLeftSibling #}

	/**
	 * Get the left sibling (adjacent) category
	 *
{# align("\t", '  ') #}
	 * @return  mixed  An array of categories or false if an error occurs
{# endalign #}
	 */
	public function &getLeftSibling()
	{
		if (!is_object($this->_item))
		{
			$this->getCategory();
		}
		return $this->_leftsibling;
	}
{# endoverride #}
{# override getRightSibling #}

	/**
	 * Get the right sibling (adjacent) category
	 *
{# align("\t", '  ') #}
	 * @return  mixed  An array of categories or false if an error occurs
{# endalign #}
	 */
	public function &getRightSibling()
	{
		if (!is_object($this->_item))
		{
			$this->getCategory();
		}
		return $this->_rightsibling;
	}
{# endoverride #}
{# override getChildren #}

	/**
	 * Get the child categories
	 *
{# align("\t", '  ') #}
	 * @return  mixed  An array of categories or false if an error occurs
{# endalign #}
	 */
	public function &getChildren()
	{
		if (!is_object($this->_item))
		{
			$this->getCategory();
		}

		// Order subcategories
		if (count($this->_children) > 0)
		{
			$params = $this->getState()->get('params');
			if ($params->get('orderby_pri') == 'alpha' || $params->get('orderby_pri') == 'ralpha')
			{
				jimport('joomla.utilities.arrayhelper');
				JArrayHelper::sortObjects($this->_children, 'title', ($params->get('orderby_pri') == 'alpha') ? 1 : -1);
			}
		}
		return $this->_children;
	}
{# endoverride #}
{# override methods #}{# endoverride #}
}
{% endif %}
