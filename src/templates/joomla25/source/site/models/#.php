<?template scope="entity"?>
{% if entity.role != 'lookup' and entity.role != 'map' %}
<?php
/**
 * {{ project.title }} {{ entity.name | title }} Frontend Item Model
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

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modelitem');

/**
 * {{ entity.name | title }} Item Model
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}
 * @since      1.0.0
{# endalign #}
 */
class {{ project.name | class }}Model{{ entity.name | class }} extends JModelItem
{
	/**
	 * Model context
	 * @var  string
	 */
	protected $_context = 'com_{{ project.name | file }}.{{ entity.name | singular | file }}';

	/**
	 * Item cache
	 * @var  stdClass[]
	 */
	protected $_item = array();
{# override properties #}{# endoverride #}
{# override populateState #}

	/**
	 * Auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
{# align("\t", '  ') #}
	 * @return  void
{# endalign #}
	 */
	protected function populateState()
	{
		$app = JFactory::getApplication('site');

		// Load state from the request.
		$pk = JRequest::getInt('{{ entity.special.key.name }}');
		$this->setState('{{ entity.name | singular | file }}.{{ entity.special.key.name }}', $pk);

		$offset = JRequest::getUInt('limitstart');
		$this->setState('list.offset', $offset);

		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);
{% if entity.special.published %}

		// TODO: Tune these values based on other permissions.
		$user = JFactory::getUser();
		if (
			!$user->authorise('core.edit.state', 'com_{{ project.name | file }}')
			&& !$user->authorise('core.edit', 'com_{{ project.name | file }}')
			&& !$user->authorise('core.edit.own', 'com_{{ project.name | file }}')
		)
		{
			$this->setState('filter.state', array(1, 2));
		}
{% endif %}

		return;
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
{# override getItem #}

	/**
	 * Get a single record
	 *
{# align("\t", '  ') #}
	 * @param	int	$pk	  The value of the primary key
	 *
	 * @return  stdClass  The normalized item data
{# endalign #}
	 *
	 * @see  {{ project.name | class }}Table{{ entity.name | plural | class }}::getBaseQuery()
	 */
	public function getItem($pk = null)
	{
		if (is_null($pk))
		{
			$pk = (int) $this->getState('{{ entity.name | singular | file }}.{{ entity.special.key.name }}');
		}

		if (!isset($this->_item[$pk]))
		{
			$user   = JFactory::getUser();
			$db     = $this->getDbo();
			$query  = $this->getTable()->getBaseQuery($user, $this->state, $this->getState('params'));
			$query->where($db->quoteName('{{ entity.name }}.{{ entity.special.key.name }}') . ' = ' . (int) $pk);
			$db->setQuery($query);
			$data = $db->loadObject();

			if ($error = $db->getErrorMsg())
			{
				throw new Exception($error);
			}

            $data->params = clone $this->getState('params');
{% if entity.special.attribs %}

			// Convert parameter fields to objects.
			$registry = new JRegistry;
			$registry->loadString($data->attribs);
			$data->params->merge($registry);
{% endif %}

{% if entity.special.metadata %}

			$registry = new JRegistry;
			$registry->loadString($data->metadata);
			$data->metadata = $registry;
{% endif %}

			// Compute selected asset permissions.
			$userId = $user->get('id');
			$data->params->set('access-edit', false);
			if (!empty($userId))
			{
{% if entity.special.category %}
				$asset  = 'com_{{ project.name | file }}.category.' . $data->catid;

{% else %}
				$asset  = 'com_{{ project.name | file }}';

{% endif %}
				if ($user->authorise('core.edit', $asset))
				{
					$data->params->set('access-edit', true);
				}
{% if entity.special.created_by %}
				elseif ($user->authorise('core.edit.own', $asset))
				{
					if ($userId == $data->{{ entity.special.created_by.name }})
					{
						$data->params->set('access-edit', true);
					}
				}
{% endif %}
			}
			$this->_item[$pk] = $data;
		}

		return $this->_item[$pk];
	}
{# endoverride #}
{# override getTable #}

	/**
	 * Return a reference to the a Table object, always creating it.
	 *
{# align("\t", '  ') #}
	 * @param	string	$type	  The table type to instantiate, defaults to '{{ entity.name | plural | class }}'
	 * @param	string	$prefix	  A prefix for the table class name, defaults to '{{ project.name | class }}Table'
	 * @param	array	$config	  Configuration array for model. Optional.
	 *
	 * @return  JTable  The table object
{# endalign #}
	 */
	public function getTable($type = '{{ entity.name | plural | class }}', $prefix = '{{ project.name | class }}Table', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
{# endoverride #}
{# override methods #}{# endoverride #}
}
{% endif %}
