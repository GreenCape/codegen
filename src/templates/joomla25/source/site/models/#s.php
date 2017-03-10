<?template scope="entity"?>
{% if entity.role != 'lookup' and entity.role != 'map' %}
<?php
/**
 * {{ project.title }} {{ entity.name | title }} Frontend List Model
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

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * {{ entity.name | title }} List Model
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}
 * @since      1.0.0
{# endalign #}
 */
class {{ project.name | class }}Model{{ entity.name | plural | class }} extends JModelList
{
	/**
	 * Model context
	 * @var  string
	 */
	protected $context = '{{ entity.name | singular | class }}';
{# override properties #}{# endoverride #}
{# override __construct #}

	/**
	 * Constructor
	 *
{# align("\t", '  ') #}
	 * @param	array	$config	  An optional associative array of configuration settings
{# endalign #}
	 *
	 * @see JController
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
{% set foreignEntity = detail.entity %}
				'num_{{ detail.entity.name | plural | variable }}_{{ detail.reference.name | variable }}', '{{ foreignEntity.name }}.{{ detail.reference.name }}',
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
	 * Auto-populate the model state
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
{# align("\t", '  ') #}
	 * @param	string	$ordering	  (opt.) The order column, defaults to {% if entity.special.ordering %}{{ entity.special.ordering.name }}{% else %}{{ entity.special.key.name }}{% endif %}
	 * @param	string	$direction	  (opt.) Order direction, one of ASC (default), DESC
	 *
	 * @return  void
{# endalign #}
	 */
	protected function populateState($ordering='{{ entity.name }}.{% if entity.special.ordering %}{{ entity.special.ordering.name }}{% endif %}{% if not entity.special.ordering %}{{ entity.special.title.name }}{% endif %}', $direction='ASC')
	{
		$app = JFactory::getApplication();

		parent::populateState($ordering, $direction);

		$params = $app->getParams();
		$this->setState('params', $params);
{% if entity.special.published %}

		$user = JFactory::getUser();
		if ((!$user->authorise('core.edit.state', 'com_{{ project.name | file }}')) &&  (!$user->authorise('core.edit', 'com_{{ project.name | file }}')))
		{
			// Limit to published for people who can't edit or edit.state.
			$this->setState('filter.state', 1);
		}
		else
		{
			$this->setState('filter.state', array(0, 1, 2));
		}
{% endif %}
{% if entity.special.language %}

		$this->setState('filter.language', $app->getLanguageFilter());
{% endif %}

		$this->setState('filter.access', !$params->get('{{ entity.name | singular | file }}_show_noauth'));
		$this->setState('layout', JRequest::getCmd('layout'));

		return;
	}
{# endoverride #}
{# override getStoreId #}

	/**
	 * Get a store id based on model configuration state.
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
{% if entity.special.published %}
		$id .= ':' . serialize($this->getState('filter.state'));
{% endif %}
		$id .= ':' . $this->getState('filter.access');
{% if entity.special.featured %}
		$id .= ':' . $this->getState('filter.featured');
{% endif %}
		$id .= ':' . $this->getState('filter.item_id');
		$id .= ':' . $this->getState('filter.item_id.include');
{% if entity.special.category %}
		$id  .= ':' . serialize($this->getState('filter.category_id'));
		$id .= ':' . $this->getState('filter.category_id.include');
{% endif %}
{% if entity.special.created_by %}
		$id  .= ':' . serialize($this->getState('filter.author_id'));
		$id .= ':' . $this->getState('filter.author_id.include');
{% endif %}
{% if entity.special.author_alias %}
		$id  .= ':' . serialize($this->getState('filter.author_alias'));
		$id .= ':' . $this->getState('filter.author_alias.include');
{% endif %}
		$id .= ':' . $this->getState('filter.date_filtering');
		$id .= ':' . $this->getState('filter.date_field');
		$id .= ':' . $this->getState('filter.start_date_range');
		$id .= ':' . $this->getState('filter.end_date_range');
		$id .= ':' . $this->getState('filter.relative_date');

		return parent::getStoreId($id);
	}
{# endoverride #}
{# override getState #}

	/**
	 * Get model state variables
	 *
{# align("\t", '  ') #}
	 * @param	string	$property	  Optional parameter name
	 * @param	mixed	$default	  Optional default value
	 *
	 * @return  mixed  The property where specified, the state object where omitted
{# endalign #}
	 */
	public function getState($property = null, $default = null)
	{
		if (is_null(parent::getState('params')))
		{
			$this->populateState();
		}
		return parent::getState($property, $default);
	}
{# endoverride #}
{# override getListQuery #}

	/**
	 * Build an SQL query to load the list data
	 *
{# align("\t", '  ') #}
	 * @return  JDatabaseQuery  The query
{# endalign #}
	 */
	protected function getListQuery()
	{
		$user   = JFactory::getUser();
		$params = $this->getState('params');
		$query  = $this->getTable()->getBaseQuery($user, $this->state, $params);

		return $query;
	}
{# endoverride #}
{# override getItems #}

	/**
	 * Get a list of {{ entity.name | plural | title }}.
	 *
{# align("\t", '  ') #}
	 * @return  mixed  An array of objects on success, false on failure
{# endalign #}
	 */
	public function getItems()
	{
		$items  = parent::getItems();
		$user   = JFactory::getUser();
		$userId = $user->get('id');

		$globalParams = JComponentHelper::getParams('com_{{ project.name | file }}', true);

		// Convert the parameter fields into objects.
		foreach ($items as $item)
		{
			$itemParams = new JRegistry;
{% if entity.special.attribs %}
			$itemParams->loadString($item->attribs);
{% endif %}

			$item->alternative_readmore = $itemParams->get('alternative_readmore');
			$item->layout               = $itemParams->get('layout');
			$item->params               = clone $this->getState('params');

			/*
			 * For blogs, {{ entity.name | singular | title }} params override menu item params only if menu param = 'use_item'
			 * Otherwise, menu item params control the layout
			 * If menu item is 'use_item' and there is no {{ entity.name | plural | title }} param, use global
			 */
			if (
				JRequest::getString('layout') == 'blog'
				|| JRequest::getString('view') == 'featured'
				|| $this->getState('params')->get('layout_type') == 'blog'
			)
			{
				// Create an array of just the params set to 'use_item'
				$menuParamsArray = $this->getState('params')->toArray();
				$itemArray       = array();

				foreach ($menuParamsArray as $key => $value)
				{
					if ($value === 'use_item')
					{
						if ($itemParams->get($key) != '')
						{
							$itemArray[$key] = $itemParams->get($key);
						}
						else
						{
							$itemArray[$key] = $globalParams->get($key);
						}
					}
				}

				// Merge the selected {{ entity.name | singular | title }} params
				if (count($itemArray) > 0)
				{
					$itemParams = new JRegistry;
					$itemParams->loadArray($itemArray);
					$item->params->merge($itemParams);
				}
			}
			else
			{
				// For non-blog layouts, merge all of the {{ entity.name | singular | title }} params
				$item->params->merge($itemParams);
			}
{% if entity.special.created or entity.special.modified or entity.special.publish_up %}

			// Get display date
			switch ($item->params->get('list_show_date'))
			{
{% if entity.special.modified %}
				case 'modified':
					$item->displayDate = $item->{{ entity.special.modified.name }};
					break;
{% endif %}
{% if entity.special.publish_up %}
				case 'published':
					$item->displayDate = {% if entity.special.created %}($item->{{ entity.special.publish_up.name }} == 0) ? $item->{{ entity.special.created.name }} : {% endif %}$item->{{ entity.special.publish_up.name }};
					break;
{% endif %}
{% if entity.special.created %}
				default:
				case 'created':
					$item->displayDate = $item->{{ entity.special.created.name }};
					break;
{% endif %}
			}
{% endif %}

			$item->params->set('access-edit', false);
{% if entity.special.published %}
			$item->params->set('access-change', false);
{% endif %}

			if (!empty($userId))
			{
{% if entity.special.category %}
				$asset  = 'com_{{ project.name | file }}.category.' . $item->catid;
{% else %}
				$asset  = 'com_{{ project.name | file }}';
{% endif %}

				if ($user->authorise('core.edit', $asset))
				{
					$item->params->set('access-edit', true);
				}
{% if entity.special.created_by %}
				elseif ($user->authorise('core.edit.own', $asset))
				{
					if ($userId == $item->{{ entity.special.created_by.name }})
					{
						$item->params->set('access-edit', true);
					}
				}
{% endif %}
{% if entity.special.published %}

				if (!empty($item->{{ entity.special.key.name }}))
				{
					$item->params->set('access-change', $user->authorise('core.edit.state', $asset));
				}
{% if entity.special.category %}
				else
				{
					$catId = (int) $this->getState('{{ entity.name | singular | file }}.catid');
					if (!empty($catId))
					{
						$item->params->set('access-change', $user->authorise('core.edit.state', 'com_{{ project.name | file }}.category.' . $catId));
						$item->catid = $catId;
					}
					else
					{
						$item->params->set('access-change', $user->authorise('core.edit.state', 'com_{{ project.name | file }}'));
					}
				}
{% else %}
				else
				{
					$item->params->set('access-change', $user->authorise('core.edit.state', 'com_{{ project.name | file }}'));
				}
{% endif %}
{% endif %}
			}
		}

		return $items;
	}
{# endoverride #}
{# override getTable #}

	/**
	 * Get a table object, load it if necessary.
	 *
{# align("\t", '  ') #}
	 * @param	string	$name	  The table name. Optional.
	 * @param	string	$prefix	  The class prefix. Optional.
	 * @param	array	$options	  Configuration array for model. Optional.
	 *
	 * @return  JTable  A JTable object
{# endalign #}
	 */
	public function getTable($name = '{{ entity.name | plural | class }}', $prefix = '{{ project.name | class }}Table', $options = array())
	{
		return parent::getTable($name, $prefix, $options);
	}
{# endoverride #}
{# override methods #}{# endoverride #}
}
{% endif %}
