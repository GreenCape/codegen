<?template scope="entity"?>
{% if entity.special.key %}
<?php
/**
 * {{ project.title }} {{ entity.name | title }} Admin List Model
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

/**
 * {{ entity.name | title }} List Model
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}
 * @since      1.0.0
{# endalign #}
 */
class {{ project.name | class }}AdminModel{{ entity.name | plural | class }} extends JModelList
{
{# override properties #}{# endoverride #}
{# override __construct #}

	/**
	 * Constructor
	 *
{# align("\t", '  ') #}
	 * @param	array	$config	  An optional associative array of configuration settings
{# endalign #}
	 */
	public function __construct($config = array())
	{
{# align(' = ') #}
		$this->option  = 'com_{{ project.name | file }}';
		$this->context  = '{{ entity.name | plural | file }}';
{# endalign #}

		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
{% if entity.role != 'map' %}
{% if entity.special.title or entity.dynName %}
{% if entity.special.title %}
				'{{ entity.special.title.name }}',
				'{{ entity.name }}.{{ entity.special.title.name }}',
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
				'num_{{detail.entity | plural | variable }}_{{detail.reference | variable }}',
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

		return;
	}
{# endoverride #}
{# override populateState #}

	/**
	 * Auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
{# align("\t", '  ') #}
	 * @param	string	$ordering	  The order column
	 * @param	string	$direction	  Order direction, one of ASC, DESC
	 *
	 * @return  void
{# endalign #}
	 */
	protected function populateState({% if entity.special.ordering %}$ordering='{{ entity.name }}.{{ entity.special.ordering.name }}', $direction='ASC'{% else %}$ordering='{{ entity.name }}.{{ entity.special.key.name }}', $direction='DESC'{% endif %})
	{
		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

{% if entity.special.published %}
		$state = $this->getUserStateFromRequest($this->context . '.filter.state', 'filter_state', '', 'string');
		$this->setState('filter.state', $state);

{% endif %}
{% if entity.special.category %}
		$categoryId = $this->getUserStateFromRequest($this->context . '.filter.category_id', 'filter_category_id', '');
		$this->setState('filter.category_id', $categoryId);

{% endif %}
{% for property in entity.references.foreignKeys  %}
{% set foreignEntity = project.entities[property.type.entity] %}
{% if property.role != 'category' %}
		${{ property.name | variable }} = $this->getUserStateFromRequest($this->context . '.filter.{{ property.name | variable }}', 'filter_{{ property.name | variable }}', '');
		$this->setState('filter.{{ property.name | variable }}', ${{ property.name | variable }});

{% endif %}
{% endfor %}
{% if entity.special.language %}
		$language = $this->getUserStateFromRequest($this->context . '.filter.language', 'filter_language', '');
		$this->setState('filter.language', $language);

{% endif %}
		// Load the parameters.
		$params = JComponentHelper::getParams('com_{{ project.name | file }}');
		$this->setState('params', $params);

		// List state information.
		parent::populateState($ordering, $direction);

		return;
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
	protected function getStoreId($id='')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
{% if entity.special.access %}
		$id .= ':' . $this->getState('filter.access');
{% endif %}
{% if entity.special.published %}
		$id .= ':' . $this->getState('filter.state');
{% endif %}
{% if entity.special.category %}
		$id .= ':' . $this->getState('filter.category_id');
{% endif %}
{% if entity.special.language %}
		$id .= ':' . $this->getState('filter.language');
{% endif %}

		return parent::getStoreId($id);
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
{# context mysql #}
		$user   = JFactory::getUser();
		$params = $this->getState('params');
		$query  = $this->getTable()->getBaseQuery($user, $this->state, $params);

		return $query;
{# endcontext #}
	}
{# endoverride #}
{# override getTable #}

	/**
	 * Return a reference to the a Table object, always creating it
	 *
{# align("\t", '  ') #}
	 * @param	string	$type	  The table type to instantiate, defaults to '{{ entity.name | class }}'
	 * @param	string	$prefix	  A prefix for the table class name, defaults to '{{ project.name | class }}Table'
	 * @param	array	$config	  Configuration array for model. Optional.
	 *
	 * @return  JTable  The table object
{# endalign #}
	 */
	public function getTable($type='{{ entity.name | plural | class }}', $prefix='{{ project.name | class }}Table', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
{# endoverride #}
{# override getItems #}
{% if entity.special.access %}

	/**
	 * Get a list of {{ entity.name | plural | title }}
	 *
	 * Overridden to add a check for access levels.
	 *
{# align("\t", '  ') #}
	 * @return  mixed  An array of {{ entity.name | plural | title }} on success, false on failure
{# endalign #}
	 */
	public function getItems()
	{
		$items = parent::getItems();
		if (JFactory::getApplication()->isSite())
		{
			$groups = JFactory::getUser()->getAuthorisedViewLevels();

			foreach ($items as $i => $item)
			{
				//Check the access level. Remove {{ entity.name | plural | title }} the user shouldn't see
				if (!in_array($item->{{ entity.special.access.name }}, $groups)) {
					unset($items[$i]);
				}
			}
		}

		return $items;
	}
{% endif %}
{# endoverride #}
{# override methods #}{# endoverride #}
}
{% endif %}
