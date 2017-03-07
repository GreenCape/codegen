<?template scope="entity"?>
{% if entity.role != 'lookup' and entity.role != 'map' and entity.special.featured %}
<?php
/**
 * {{ project.title }} {{ entity.name | title }} Featured Items Controller
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

require_once dirname(__FILE__) . '/{{ entity.name | plural | file }}.php';

/**
 * Frontpage Component Model
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}
 * @since      1.0.0
{# endalign #}
 */
class {{ project.name | class }}ModelFeatured extends {{ project.name | class }}Model{{ entity.name | plural | class }}
{
	/**
	 * Model context
	 * @var  string
	 */
	public $_context = 'com_{{ project.name | file }}.frontpage';
{# override properties #}{# endoverride #}
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
		parent::populateState($ordering, $direction);

		// List state information
		$limitstart = JRequest::getUInt('limitstart', 0);
		$this->setState('list.start', $limitstart);

		$params = $this->state->params;
		$limit = $params->get('num_leading_items') + $params->get('num_intro_items') + $params->get('num_links');
		$this->setState('list.limit', $limit);
		$this->setState('list.links', $params->get('num_links'));

		$this->setState('filter.frontpage', true);

		$user     = JFactory::getUser();

		if ((!$user->authorise('core.edit.state', 'com_{{ project.name | file }}')) &&  (!$user->authorise('core.edit', 'com_{{ project.name | file }}'))){
			// filter on published for those who do not have edit or edit.state rights.
			$this->setState('filter.state', 1);
		}
		else {
			$this->setState('filter.state', array(0, 1, 2));
		}

		// check for category selection
		if ($params->get('featured_categories') && implode(',', $params->get('featured_categories'))  == true) {
			$featuredCategories = $params->get('featured_categories');
			 $this->setState('filter.frontpage.categories', $featuredCategories);
		 }
	}
{# endoverride #}
{# override getItems #}

	/**
	 * Get a list of {{ entity.name | plural | title }}
	 *
{# align("\t", '  ') #}
	 * @return  mixed  An array of objects on success, false on failure
{# endalign #}
	 */
	public function getItems()
	{
		$params = clone $this->getState('params');
		$limit = $params->get('num_leading_items') + $params->get('num_intro_items') + $params->get('num_links');

		if ($limit > 0)
		{
			$this->setState('list.limit', $limit);
			return parent::getItems();
		}

		return array();
	}
{# endoverride #}
{# override getStoreId #}

	/**
	 * Get a store id based on model configuration state
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
{# align("\t", '  ') #}
	 * @param	string	$id	  A prefix for the store id
	 *
	 * @return  string  A store id
{# endalign #}
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= $this->getState('filter.frontpage');

		return parent::getStoreId($id);
	}
{# endoverride #}
{# endoverride getListQuery #}

	/**
	 *
{# align("\t", '  ') #}
	 * @return  JDatabaseQuery
{# endalign #}
   */
	public function getListQuery()
	{
		// Set the blog ordering
		$params = $this->state->params;
		$itemOrderby = $params->get('orderby_sec', 'rdate');
		$itemOrderDate = $params->get('order_date');
		$categoryOrderby = $params->def('orderby_pri', '');
		$secondary = {{ project.name | class }}HelperQuery::order{{ entity.name | plural | class }}BySecondary($itemOrderby, $itemOrderDate) . ', ';
		$primary = {{ project.name | class }}HelperQuery::orderbyPrimary($categoryOrderby);

		$orderby = $primary . ' ' . $secondary . ' {{ entity.name }}.created DESC ';
		$this->setState('list.ordering', $orderby);
		$this->setState('list.direction', '');
		// Create a new query object.
		$query = parent::getListQuery();

		// Filter by frontpage.
		if ($this->getState('filter.frontpage'))
		{
			$query->join('INNER', '#__content_frontpage AS fp ON fp.content_id = {{ entity.name }}.id');
		}

		// Filter by categories
		if (is_array($featuredCategories = $this->getState('filter.frontpage.categories'))) {
			$query->where('{{ entity.name }}.catid IN (' . implode(',', $featuredCategories) . ')');
		}

		return $query;
	}
{# endoverride #}
{# override methods #}{# endoverride #}
}
{% endif %}
