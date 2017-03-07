<?template scope="entity"?>
{% if entity.role != 'lookup' and entity.role != 'map' and entity.special.published %}
<?php
/**
 * {{ project.title }} {{ entity.name | title }} Archive Controller
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

require_once dirname(__FILE__) . '/{{ entity.name | singular | file }}.php';

/**
 * {{ entity.name | title }} Archive Model
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}
 * @since      1.0.0
{# endalign #}
 */
class {{ project.name | class }}Model{{ entity.name | class }}_Archive extends {{ project.name | class }}Model{{ entity.name | plural | class }}
{
	/**
	 * Model context
	 * @var  string
	 */
	public $_context = 'com_{{ project.name | file }}.archive';
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
		parent::populateState();

		// Add archive properties
		$params = $this->state->params;

		// Filter on archived {{ entity.name | plural | title }}
		$this->setState('filter.state', 2);

		// Filter on month, year
		$this->setState('filter.month', JRequest::getInt('month'));
		$this->setState('filter.year', JRequest::getInt('year'));

		// Optional filter text
		$this->setState('list.filter', JRequest::getString('filter-search'));

		// Get list limit
		$app = JFactory::getApplication();
		$itemid = JRequest::getInt('Itemid', 0);
		$limit = $app->getUserStateFromRequest('com_{{ project.name | file }}.archive.list' . $itemid . '.limit', 'limit', $params->get('display_num'), 'uint');
		$this->setState('list.limit', $limit);
	}
{# endoverride #}
{# override getListQuery #}

	/**
	 *
{# align("\t", '  ') #}
	 * @return  JDatabaseQuery
{# endalign #}
   */
	public function getListQuery()
	{
		// Set the archive ordering
		$params = $this->state->params;
		$itemOrderby = $params->get('orderby_sec', 'rdate');
		$itemOrderDate = $params->get('order_date');

		// No category ordering
		$categoryOrderby = '';
		$secondary = {{ project.name | class }}HelperQuery::order{{ entity.name | plural | class }}BySecondary($itemOrderby, $itemOrderDate) . ', ';
		$primary = {{ project.name | class }}HelperQuery::orderbyPrimary($categoryOrderby);

		$orderby = $primary . ' ' . $secondary . ' {{ entity.name }}.created DESC ';
		$this->setState('list.ordering', $orderby);
		$this->setState('list.direction', '');
		// Create a new query object.
		$query = parent::getListQuery();

		// Add routing for archive
		$case_when = ' CASE WHEN ';
		$case_when .= $query->charLength('{{ entity.name }}.alias');
		$case_when .= ' THEN ';
		$a_id = $query->castAsChar('{{ entity.name }}.id');
		$case_when .= $query->concatenate(array($a_id, '{{ entity.name }}.alias'), ':');
		$case_when .= ' ELSE ';
		$case_when .= $a_id.' END as slug';
		$query->select($case_when);

		$case_when = ' CASE WHEN ';
		$case_when .= $query->charLength('c.alias');
		$case_when .= ' THEN ';
		$c_id = $query->castAsChar('c.id');
		$case_when .= $query->concatenate(array($c_id, 'c.alias'), ':');
		$case_when .= ' ELSE ';
		$case_when .= $c_id.' END as catslug';
		$query->select($case_when);

		// Filter on month, year
		$queryDate = {{ project.name | class }}HelperQuery::getQueryDate($itemOrderDate);

		if ($month = $this->getState('filter.month')) {
			$query->where('MONTH('. $queryDate . ') = ' . $month);
		}

		if ($year = $this->getState('filter.year')) {
			$query->where('YEAR('. $queryDate . ') = ' . $year);
		}

		return $query;
	}
{# endoverride #}
{# override getData #}

	/**
	 * Get the archived article list
	 *
{# align("\t", '  ') #}
	 * @return  array
{# endalign #}
   */
	public function getData()
	{
		$app = JFactory::getApplication();

		// Lets load the content if it doesn't already exist
		if (empty($this->_data)) {
			// Get the page/component configuration
			$params = $app->getParams();

			// Get the pagination request variables
			$limit     = JRequest::getUInt('limit', $params->get('display_num', 20));
			$limitstart = JRequest::getUInt('limitstart', 0);

			$query = $this->_buildQuery();

			$this->_data = $this->_getList($query, $limitstart, $limit);
		}

		return $this->_data;
	}
{# endoverride #}
{# override _getList #}

	/**
	 * JModel override to add alternating value for $odd
	 */
	protected function _getList($query, $limitstart=0, $limit=0)
	{
		$result = parent::_getList($query, $limitstart, $limit);

		$odd = 1;
		foreach ($result as $k => $row) {
			$result[$k]->odd = $odd;
			$odd = 1 - $odd;
		}

		return $result;
	}
{# endoverride #}
{# override methods #}{# endoverride #}
}
{% endif %}
