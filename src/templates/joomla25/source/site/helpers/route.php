<?template scope="application"?>
<?php
/**
 * {{ project.title }} Frontend Route Helper
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

jimport('joomla.application.component.helper');
jimport('joomla.application.categories');

/**
 * {{ project.title }} Route Helper
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}
 * @since      1.0.0
{# endalign #}
 */
class {{ project.name | class }}HelperRoute
{
	/**
	 * Internal lookup table
	 * @var  array
	 */
	protected static $lookup;
{# override properties #}{# endoverride #}
{% for entity in project.entities %}
{% if entity.role != 'lookup' and entity.role != 'map' %}
{# override get{{ entity.name | class }}Route #}

	/**
	 * Get the route for a single {{ entity.name | singular | title }}
	 *
{# align("\t", '  ') #}
	 * @param	int	$id	  The {{ entity.name | singular | title }} id
{% if entity.special.category %}
	 * @param	int	$catid	  The id of the containing category
{% endif %}
{% if entity.special.language %}
	 * @param	int	$language	  {% endif %}
	 *
	 * @return  string
{# endalign #}
	 */
	public static function get{{ entity.name | class }}Route($id{% if entity.special.category %}, $catid = 0{% endif %}{% if entity.special.language %}, $language = 0{% endif %})
	{
		$needles = array(
			'{{ entity.name | singular | file }}' => array((int) $id)
		);
		$link = 'index.php?option=com_{{ project.name | file }}&view={{ entity.name | singular | file }}&id=' . $id;
{% if entity.special.category %}

		if ((int) $catid > 1)
		{
			$categories = JCategories::getInstance('{{ project.name | class }}.{{ entity.name | class }}');
			$category = $categories->get((int) $catid);
			if (!empty($category))
			{
				$needles['category'] = array_reverse($category->getPath());
				$needles['categories'] = $needles['category'];
				$link .= '&{{ entity.special.category.name }}=' . $catid;
			}
		}
{% endif %}
{% if entity.special.language %}

		if ($language && $language != "*" && JLanguageMultilang::isEnabled())
		{
			$db    = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select('a.sef AS sef');
			$query->select('a.lang_code AS lang_code');
			$query->from('#__languages AS a');
			//$query->where('a.lang_code = ' .$language);
			$db->setQuery($query);
			$langs = $db->loadObjectList();
			foreach ($langs as $lang)
			{
				if ($language == $lang->lang_code)
				{
					$language = $lang->sef;
					$link .= '&lang=' . $language;
				}
			}
		}
{% endif %}

		$link .= self::getItemId($needles);

		return $link;
	}
{# endoverride #}
{# override get{{ entity.name | class }}FormRoute #}

	/**
	 * Get the route to the form for a single {{ entity.name | singular | title }}
	 *
{# align("\t", '  ') #}
	 * @param	int	$id	  The {{ entity.name | singular | title }} id
	 *
	 * @return  string
{# endalign #}
	 */
	public static function get{{ entity.name | class }}FormRoute($id)
	{
		if (empty($id))
		{
			$id = '0';
		}
		$link = 'index.php?option=com_{{ project.name | file }}&task={{ entity.name | singular | file }}.edit&id=' . $id;

		return $link;
	}
{# endoverride #}
{% if entity.special.category %}
{# override get{{ entity.name | class }}CategoryRoute #}

	/**
	 * Get the route for a category
	 *
{# align("\t", '  ') #}
	 * @param	int	$catid	  The id of the category
	 *
	 * @return  string
{# endalign #}
	 */
	public static function get{{ entity.name | class }}CategoryRoute($catid)
	{
		if ($catid instanceof JCategoryNode)
		{
			$id       = $catid->id;
			$category = $catid;
		}
		else
		{
			$id       = (int) $catid;
			$category = JCategories::getInstance('{{ project.name | class }}.{{ entity.name | class }}')->get($id);
		}

		if ($id < 1)
		{
			$link = '';
		}
		else
		{
			$needles = array(
				'category' => array($id)
			);

			$item = self::_findItem($needles);
			if (!empty($item))
			{
				$link = 'index.php?Itemid=' . $item;
			}
			else
			{
				$link = 'index.php?option=com_{{ project.name | file }}&view={{ entity.name | singular | file }}_category&id=' . $id;
				if (!empty($category))
				{
					$catids  = array_reverse($category->getPath());
					$needles = array(
						'category'   => $catids,
						'categories' => $catids
					);
					$link .= self::getItemId($needles);
				}
			}
		}

		return $link;
	}
{# endoverride #}
{% endif %}
{% endif %}
{% endfor %}
{# override _findItem #}

	/**
	 * Find an Itemid
	 *
{# align("\t", '  ') #}
	 * @param	array	$needles	  A list of identifying information
	 *
	 * @return  int  The Itemid or null if not found
{# endalign #}
	 */
	protected static function _findItem($needles = null)
	{
		$app   = JFactory::getApplication();
		$menus = $app->getMenu('site');

		// Prepare the reverse lookup array.
		if (self::$lookup === null)
		{
			$component = JComponentHelper::getComponent('com_{{ project.name | file }}');
			$items     = $menus->getItems('component_id', $component->id);
			self::initializeLookup($items);
		}

		if ($needles)
		{
			foreach ($needles as $view => $ids)
			{
				if (isset(self::$lookup[$view]))
				{
					foreach ($ids as $id)
					{
						if (isset(self::$lookup[$view][(int) $id]))
						{
							return self::$lookup[$view][(int) $id];
						}
					}
				}
			}
		}
		else
		{
			$active = $menus->getActive();
			if (!empty($active))
			{
				return $active->id;
			}
		}

		return null;
	}

	/**
	 * Initialize the internal lookup table
	 *
{# align("\t", '  ') #}
	 * @param	array	$items	  A list of menu items
	 *
	 * @return  void
{# endalign #}
	 */
	protected static function initializeLookup($items)
	{
		self::$lookup = array();

		foreach ($items as $item)
		{
			if (isset($item->query) && isset($item->query['view']))
			{
				$view = $item->query['view'];
				if (!isset(self::$lookup[$view]))
				{
					self::$lookup[$view] = array();
				}
				if (isset($item->query['id']))
				{
					self::$lookup[$view][$item->query['id']] = $item->id;
				}
			}
		}

		return;
	}
{# endoverride #}
{# override getItemId #}

	/**
	 * Build the Itemid request parameter
	 *
{# align("\t", '  ') #}
	 * @param	array	$needles	  A list of identifying information
	 *
	 * @return  string
{# endalign #}
	 */
	public static function getItemId($needles)
	{
		$link = '';
		$item = self::_findItem($needles);
		if (empty($item))
		{
			$item = self::_findItem();
		}
		if (!empty($item))
		{
			$link .= '&Itemid=' . $item;
		}
		return $link;
	}
{# endoverride #}
{# override methods #}{# endoverride #}
}
