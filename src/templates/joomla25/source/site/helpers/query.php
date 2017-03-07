<?template scope="application"?>
<?php
/**
 * {{ project.title }} Frontend Query Helper
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
 * {{ project.title }} Query Helper
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}
 * @since      1.0.0
{# endalign #}
 */
class {{ project.name | class }}HelperQuery
{
{# override properties #}{# endoverride #}
{# override orderbyPrimary #}
	/**
	 * Translate an order code to a field for primary category ordering
	 *
{# align("\t", '  ') #}
	 * @param	string	$orderby	  The ordering code
	 *
	 * @return  string  The SQL field(s) to order by
{# endalign #}
	 */
	public static function orderbyPrimary($orderby)
	{
		switch ($orderby)
		{
			case 'alpha' :
				$orderby = 'c.path, ';
				break;

			case 'ralpha' :
				$orderby = 'c.path DESC, ';
				break;

			case 'order' :
				$orderby = 'c.lft, ';
				break;

			default :
				$orderby = '';
				break;
		}

		return $orderby;
	}

{# endoverride #}
{% for entity in project.entities %}
{% if entity.role != 'lookup' and entity.role != 'map' %}
{# override {{ entity.name | plural | class }}BySecondary #}
	/**
	 * Translate an order code to a field for secondary {{ entity.name | class }} Category ordering
	 *
{# align("\t", '  ') #}
	 * @param	string	$orderby	  The ordering code
	 * @param	string	$orderDate	  The ordering code for the date
	 *
	 * @return  string  The SQL field(s) to order by
{# endalign #}
	 */
	public static function order{{ entity.name | plural | class }}BySecondary($orderby, $orderDate = 'created')
	{
		$queryDate = self::get{{ entity.name | class }}QueryDate($orderDate);

		switch ($orderby)
		{
			case 'date':
				$orderby = $queryDate;
				break;

			case 'rdate':
				$orderby = $queryDate . ' DESC ';
				break;

			case 'alpha':
				$orderby = '{{ entity.name }}.{{ entity.special.title.name }}';
				break;

			case 'ralpha':
				$orderby = '{{ entity.name }}.{{ entity.special.title.name }} DESC';
				break;
{% if entity.special.hits %}

			case 'hits':
				$orderby = '{{ entity.name }}.{{ entity.special.hits.name }} DESC';
				break;

			case 'rhits':
				$orderby = '{{ entity.name }}.{{ entity.special.hits.name }}';
				break;
{% endif %}
{% if entity.special.ordering %}

			case 'order':
				$orderby = '{{ entity.name }}.{{ entity.special.ordering.name }}';
				break;
{% endif %}
{% if entity.special.created_by %}

			case 'author':
				$orderby = '{{ entity.name }}.{{ entity.special.created_by.name }}';
				break;

			case 'rauthor':
				$orderby = '{{ entity.name }}.{{ entity.special.created_by.name }} DESC';
				break;
{% endif %}
{% if entity.special.featured and entity.special.ordering %}

			case 'front':
				$orderby = 'fp.{{ entity.special.ordering.name }}';
				break;
{% endif %}

			default:
{% if entity.special.ordering %}
				$orderby = '{{ entity.name }}.{{ entity.special.ordering.name }}';
{% else %}

				$orderby = '{{ entity.name }}.{{ entity.special.key.name }} DESC';
{% endif %}
				break;
		}

		return $orderby;
	}
{# endoverride #}
{# override get{{ entity.name | class }}QueryDate #}

	/**
	 * Translate an order code to a field for secondary category ordering
	 *
{# align("\t", '  ') #}
	 * @param	string	$orderDate	  The ordering code
	 *
	 * @return  string  The SQL field(s) to order by
{# endalign #}
	 */
	public static function get{{ entity.name | class }}QueryDate($orderDate)
	{
		switch ($orderDate)
		{
{% if entity.special.created %}
{% if entity.special.modified %}
			case 'modified' :
				$queryDate = 'CASE WHEN {{ entity.name }}.{{ entity.special.modified.name }} = 0 THEN {{ entity.name }}.{{ entity.special.created.name }} ELSE {{ entity.name }}.{{ entity.special.modified.name }} END';
				break;

{% endif %}
{% if entity.special.publish_up %}
			case 'published' :
				$queryDate = 'CASE WHEN {{ entity.name }}.{{ entity.special.publish_up.name }} = 0 THEN {{ entity.name }}.{{ entity.special.created.name }} ELSE {{ entity.name }}.{{ entity.special.publish_up.name }} END';
				break;

{% endif %}
			case 'created' :
			default :
				$queryDate = '{{ entity.name }}.{{ entity.special.created.name }}';
				break;

{% else %}
{% if entity.special.modified %}
			case 'modified' :
				$queryDate = 'CASE WHEN {{ entity.name }}.{{ entity.special.modified.name }} = 0 THEN {{ entity.name }}.{{ entity.special.key.name }} ELSE {{ entity.name }}.{{ entity.special.modified.name }} END';
				break;

{% endif %}
{% if entity.special.publish_up %}
			case 'published' :
				$queryDate = 'CASE WHEN {{ entity.name }}.{{ entity.special.publish_up.name }} = 0 THEN {{ entity.name }}.{{ entity.special.key.name }} ELSE {{ entity.name }}.{{ entity.special.publish_up.name }} END';
				break;

{% endif %}
			default :
				$queryDate = '{{ entity.name }}.{{ entity.special.key.name }}';
				break;
{% endif %}
		}
		return $queryDate;
	}
{# endoverride #}
{% endif %}
{% endfor %}
{# override orderDownColumns #}

	/**
	 * Method to order the intro articles array for ordering
	 * down the columns instead of across
     *
	 * The layout always lays the introtext articles out across columns.
	 * Array is reordered so that, when articles are displayed in index order
	 * across columns in the layout, the result is that the
	 * desired article ordering is achieved down the columns.
	 *
{# align("\t", '  ') #}
	 * @param   array  &$articles  Array of intro text articles
	 * @param	integer	$numColumns	  Number of columns in the layout
	 *
	 * @return  array  Reordered array to achieve desired ordering down columns
{# endalign #}
	 */
	public static function orderDownColumns(&$articles, $numColumns = 1)
	{
		$count = count($articles);

		// Just return the same array if there is nothing to change
		if ($numColumns == 1 || !is_array($articles) || $count <= $numColumns)
		{
			$return = $articles;
		}
		// We need to re-order the intro articles array
		else
		{
			// We need to preserve the original array keys
			$keys = array_keys($articles);

			$maxRows = ceil($count / $numColumns);
			$numCells = $maxRows * $numColumns;
			$numEmpty = $numCells - $count;
			$index = array();

			/*
			 * Calculate number of empty cells in the array
			 * Fill in all cells of the array
			 * Put -1 in empty cells so we can skip later
			 */
			for ($row = 1, $i = 1; $row <= $maxRows; $row++)
			{
				for ($col = 1; $col <= $numColumns; $col++)
				{
					if ($numEmpty > ($numCells - $i))
					{
						// Put -1 in empty cells
						$index[$row][$col] = -1;
					}
					else
					{
						// Put in zero as placeholder
						$index[$row][$col] = 0;
					}
					$i++;
				}
			}

			// Layout the articles in column order, skipping empty cells
			$i = 0;
			for ($col = 1; ($col <= $numColumns) && ($i < $count); $col++)
			{
				for ($row = 1; ($row <= $maxRows) && ($i < $count); $row++)
				{
					if ($index[$row][$col] != - 1)
					{
						$index[$row][$col] = $keys[$i];
						$i++;
					}
				}
			}

			/*
			 * Now read the $index back row by row to get articles in right row/col
			 * so that they will actually be ordered down the columns (when read by row in the layout)
			 */
			$return = array();
			$i = 0;
			for ($row = 1; ($row <= $maxRows) && ($i < $count); $row++)
			{
				for ($col = 1; ($col <= $numColumns) && ($i < $count); $col++)
				{
					$return[$keys[$i]] = $articles[$index[$row][$col]];
					$i++;
				}
			}
		}

		return $return;
	}
{# endoverride #}
{# override methods #}{# endoverride #}
}
