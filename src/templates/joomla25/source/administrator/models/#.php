<?template scope="entity"?>
{% if entity.special.key %}
<?php
/**
 * {{ project.title }} {{ entity.name | title }} Admin Item Model
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

// No direct access.
defined('_JEXEC') or die;

/**
 * {{ entity.name | title }} Item Model
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}
 * @since      1.0.0
{# endalign #}
 */
class {{ project.name | class }}AdminModel{{ entity.name | class }} extends JModelAdmin
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
		$this->option = 'com_{{ project.name | file }}';
		$this->context = '{{ entity.name | class }}';
		$this->text_prefix = 'COM_{{ project.name | constant }}_{{ entity.name | constant }}';
{# endalign #}

		parent::__construct($config);
		return;
	}
{# endoverride #}
{# override publish #}
{% if entity.special.category %}
{# override batchCopy #}

	/**
	 * Batch copy items to a new category or current
	 *
{# align("\t", '  ') #}
	 * @param	int	$value	  The new category
	 * @param	array	$pks	  An array of row IDs
	 *
	 * @return  mixed  An array of new IDs on success, boolean false on failure
{# endalign #}
	 */
	protected function batchCopy($value, $pks)
	{
		$categoryId = (int) $value;
		$table      = $this->getTable();

		if (empty($categoryId))
		{
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_MOVE_CATEGORY_NOT_FOUND'));
			return false;
		}
		$categoryTable = JTable::getInstance('Category');
		if (!$categoryTable->load($categoryId))
		{
			$error = $categoryTable->getError();
			if (empty($error))
			{
				$error = JText::_('JLIB_APPLICATION_ERROR_BATCH_MOVE_CATEGORY_NOT_FOUND');
			}
			$this->setError($error);
			return false;
		}

		$extension = JFactory::getApplication()->input->get('option', '');
		$user = JFactory::getUser();
		if (!$user->authorise('core.create', $extension . '.category.' . $categoryId))
		{
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_CREATE'));
			return false;
		}

		$newIds = array();
		while (!empty($pks))
		{
			$pk = array_shift($pks);
			$newId = $this->copyEntry($table, $categoryId, $pk);
			if (!empty($newId))
			{
				$newIds[] = $newId;
			}
		}
		$this->cleanCache();

		return $newIds;
	}
{# endoverride #}
{# override copyEntry #}

	/**
	 * Copy a single entry
	 *
{# align("\t", '  ') #}
	 * @param	JTable	$table	  The table containing the entry
	 * @param	int	$categoryId	  The target category
	 * @param	int	$pk	  The primary key value
	 *
	 * @return  int
{# endalign #}
	 */
	protected function copyEntry($table, $categoryId, $pk)
	{
		$table->reset();

		if (!$table->load($pk))
		{
			$error = $table->getError();
			if (empty($error))
			{
				$error = JText::sprintf('JLIB_APPLICATION_ERROR_BATCH_MOVE_ROW_NOT_FOUND', $pk);
			}
			$this->setError($error);
			return 0;
		}

		$data = $this->generateNewTitle($categoryId, {% if entity.special.alias %}$table->{{ entity.special.alias.name }}{% else %}null{% endif %}, $table->{{ entity.special.title.name }});
		$table->{{ entity.special.title.name }} = $data['0'];
{% if entity.special.alias %}
		$table->{{ entity.special.alias.name }} = $data['1'];
{% endif %}
		$table->{{ entity.special.key.name }} = 0;
		$table->{{ entity.special.category.name }} = $categoryId;

		// TODO: Deal with ordering?

		if (!$table->check() || !$table->store())
		{
			$this->setError($table->getError());
			return 0;
		}

		return $table->get('{{ entity.special.key.name }}');
	}
{# endoverride #}
{# override canDelete #}

	/**
	 * Test whether a record can be deleted.
	 *
{# align("\t", '  ') #}
	 * @param	object	$record	  A record object.
	 *
	 * @return  bool  True if allowed to delete the record. Defaults to the permission set in the component.
{# endalign #}
	 */
	protected function canDelete($record)
	{
		if (!empty($record->{{ entity.special.key.name }}))
		{
{% if entity.special.published %}
			if ($record->{{ entity.special.published.name }} != -2)
			{
				return false;
			}

{% endif %}
			if ($record->{{ entity.special.category.name }})
			{
				return JFactory::getUser()->authorise('core.delete', 'com_{{ project.name | file }}.category.' . (int) $record->{{ entity.special.category.name }});
			}
			else
			{
				return parent::canDelete($record);
			}
		}
	}
{# endoverride #}
{# override canEditState #}

	/**
	 * Test whether a record can have its state edited.
	 *
{# align("\t", '  ') #}
	 * @param	object	$record	  A record object.
	 *
	 * @return  bool  True if allowed to change the state of the record. Defaults to the permission set in the component.
{# endalign #}
	 */
	protected function canEditState($record)
	{
		$user = JFactory::getUser();

		if (!empty($record->{{ entity.special.key.name }}))
		{
			return $user->authorise('core.edit.state', 'com_{{ project.name | file }}.{{ entity.name | singular | file }}.' . (int) $record->{{ entity.special.key.name }});
		}
		elseif (!empty($record->{{ entity.special.category.name }}))
		{
			return $user->authorise('core.edit.state', 'com_{{ project.name | file }}.category.' . (int) $record->{{ entity.special.category.name }});
		}
		else
		{
			return parent::canEditState('com_{{ project.name | file }}');
		}
	}
{# endoverride #}
{% endif %}
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
{# override getItem #}
{% if entity.special.attribs %}

	/**
	 * Get a single record.
	 *
{# align("\t", '  ') #}
	 * @param	int	$pk	  The id of the primary key
	 *
	 * @return  mixed  Object on success, false on failure
{# endalign #}
	 */
	public function getItem($pk = null)
	{
		$item = parent::getItem($pk);
		if (!empty($item))
		{
			$registry = new JRegistry;
			$registry->loadString($item->{{ entity.special.attribs.name }});
			$item->{{ entity.special.attribs.name }} = $registry->toArray();
		}
		return $item;
	}
{% endif %}
{# endoverride #}
{# override getForm #}

	/**
	 * Get the record form
	 *
{# align("\t", '  ') #}
	 * @param	array	$data	  Data for the form
	 * @param	bool	$loadData	  true if the form is to load its own data (default case), false if not
	 *
	 * @return  JForm  A JForm object on success, false on failure
{# endalign #}
	 */
	public function getForm($data = array(), $loadData = true)
	{
		$form = $this->loadForm('com_{{ project.name | file }}.{{ entity.name | singular | file }}', '{{ entity.name | singular | file }}', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		if (!$loadData)
		{
			$form->bind($data);
		}
{% if entity.special.category %}

		if ($this->getState('{{ entity.name | singular | file }}.id'))
		{
			$form->setFieldAttribute('{{ entity.special.category.name }}', 'action', 'core.edit');
		}
		else
		{
			$form->setFieldAttribute('{{ entity.special.category.name }}', 'action', 'core.create');
		}
{% endif %}
{% if entity.special.ordering or entity.special.publish_up or entity.special.publish_down or entity.special.published or entity.special.sticky %}

		if (!$this->canEditState((object) $data))
		{
			// Disable fields for display
{% if entity.special.ordering %}
			$form->setFieldAttribute('{{ entity.special.ordering.name }}', 'disabled', 'true');
{% endif %}
{% if entity.special.publish_up %}
			$form->setFieldAttribute('{{ entity.special.publish_up.name }}', 'disabled', 'true');
{% endif %}
{% if entity.special.publish_down %}
			$form->setFieldAttribute('{{ entity.special.publish_down.name }}', 'disabled', 'true');
{% endif %}
{% if entity.special.published %}
			$form->setFieldAttribute('{{ entity.special.published.name }}', 'disabled', 'true');
{% endif %}
{% if entity.special.sticky %}
			$form->setFieldAttribute('{{ entity.special.sticky.name }}', 'disabled', 'true');
{% endif %}

			// Disable fields while saving
{% if entity.special.ordering %}
			$form->setFieldAttribute('{{ entity.special.ordering.name }}', 'filter', 'unset');
{% endif %}
{% if entity.special.publish_up %}
			$form->setFieldAttribute('{{ entity.special.publish_up.name }}', 'filter', 'unset');
{% endif %}
{% if entity.special.publish_down %}
			$form->setFieldAttribute('{{ entity.special.publish_down.name }}', 'filter', 'unset');
{% endif %}
{% if entity.special.published %}
			$form->setFieldAttribute('{{ entity.special.published.name }}', 'filter', 'unset');
{% endif %}
{% if entity.special.sticky %}
			$form->setFieldAttribute('{{ entity.special.sticky.name }}', 'filter', 'unset');
{% endif %}
		}
{% endif %}

		return $form;
	}
{# endoverride #}
{# override loadFormData #}

	/**
	 * Get the data that should be injected in the form
	 *
{# align("\t", '  ') #}
	 * @return  mixed  The data for the form
{# endalign #}
	 */
	protected function loadFormData()
	{
		$app = JFactory::getApplication();

		// Check the session for previously entered form data.
		$data = $app->getUserState('com_{{ project.name | file }}.edit.{{ entity.name | singular | file }}.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
{% if entity.special.category %}

			// Prime some default values
			if ($this->getState('{{ entity.name | singular | file }}.id') == 0)
			{
				$data->set('{{ entity.special.category.name }}', JRequest::getInt('{{ entity.special.category.name }}', $app->getUserState('com_{{ project.name | file }}.{{ entity.name | singular | file }}.filter.category_id')));
			}
{% endif %}
		}

		return $data;
	}
{# endoverride #}
{# override save #}
{% if entity.special.attribs or entity.special.featured %}

	/**
	 * Save the form data
	 *
{# align("\t", '  ') #}
	 * @param	array	$data	  The form data
	 *
	 * @return  bool  True on success
{# endalign #}
	 */
	public function save($data)
	{
		if (JRequest::getVar('task') == 'save2copy')
		{
			list(${{ entity.special.title.name }}, $alias) = $this->generateNewTitle({% if entity.special.category %}$data['{{ entity.special.category.name }}'], {% endif %}{% if entity.special.alias %}$data['{{ entity.special.alias.name }}']{% else %}null{% endif %}, $data['{{ entity.special.title.name }}']);

			$data['{{ entity.special.title.name }}'] = ${{ entity.special.title.name }};
{% if entity.special.alias %}
			$data['{{ entity.special.alias.name }}'] = ${{ entity.special.alias.name }};
{% endif %}
			$data['{{ entity.special.key.name }}'] = 0;
		}
{% if entity.special.featured %}

		if (parent::save($data))
		{
			if (isset($data['featured']))
			{
				$this->featured($this->getState($this->getName().'.id'), $data['featured']);
			}

			return true;
		}

		return false;
{% else %}

		return parent::save($data);
{% endif %}
	}
{% endif %}
{# endoverride #}
{% if entity.special.featured %}
{# override featured #}

	/**
	 * Set or unset the featured setting of {{ entity.name | plural | title }}
	 *
{# align("\t", '  ') #}
	 * @param   array  &$pks  The ids of the {{ entity.name | plural | title }} to feature
	 * @param	int	$value	  The value to toggle to.
	 *
	 * @return  bool  True on success
{# endalign #}
	 */
	public function featured($pks, $value = 0)
	{
		return $this->setFlag($pks, $value, 'featured');
	}
{# endoverride #}
{% endif %}
{% if entity.special.sticky %}
{# override stick #}

	/**
	 * Set or unset the stick setting of {{ entity.name | plural | title }}
	 *
{# align("\t", '  ') #}
	 * @param   array  &$pks  The ids of the {{ entity.name | plural | title }} to stick
	 * @param	int	$value	  The value of the sticky fla
	 *
	 * @return  bool  True on success
{# endalign #}
	 */
	function stick(&$pks, $value = 1)
	{
		return $this->setFlag($pks, $value, 'stick');
	}
{# endoverride #}
{% endif %}
{% if entity.special.featured or entity.special.sticky %}
{# override setFlag #}

	/**
	 * Set or unset a flag on a bunch of {{ entity.name | title }} records
	 *
{# align("\t", '  ') #}
	 * @param   array  &$pks  The ids of the {{ entity.name | plural | title }} to flag
	 * @param	int	$value	  The value of the sticky flag
	 * @param	string	$method	  The method of the table class to set/unset the flag
	 *
	 * @return  bool  True on success
{# endalign #}
	 */
	protected function setFlag(&$pks, $value = 1, $method)
	{
		$user = JFactory::getUser();
		$table = $this->getTable();
		$pks = (array)$pks;
		JArrayHelper::toInteger($pks);

		// Access checks
		foreach ($pks as $i => $pk)
		{
			if ($table->load($pk))
			{
				if (!$this->canEditState($table))
				{
					// Prune items that this user can't change
					unset($pks[$i]);
					JError::raiseWarning(403, JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'));
				}
			}
		}

		// Attempt to change the state of the {{ entity.name | plural | title }}
		if (!$table->$method($pks, $value, $user->get('id')))
		{
			$this->setError($table->getError());

			return false;
		}

		return true;
	}
{# endoverride #}
{% endif %}
{% if entity.special.ordering %}
{# override getReorderConditions #}

	/**
	 * Get a set of ordering conditions
	 *
{# align("\t", '  ') #}
	 * @param	object	$table	  A record object
	 *
	 * @return  array  An array of conditions to add to add to ordering queries
{# endalign #}
	 */
	protected function getReorderConditions($table)
	{
		$condition = array();
{% if entity.special.category %}
		$condition[] = '{{ entity.special.category.name }}=' . (int) $table->{{ entity.special.category.name }};
{% endif %}

		return $condition;
	}
{# endoverride #}
{% endif %}
{# override prepareTable #}

	/**
	 * Prepare and sanitise the table prior to saving
	 *
{# align("\t", '  ') #}
{% if entity.special.publish_up or entity.special.version or entity.special.ordering %}
	 * @param   {{ project.name | class }}Table{{ entity.name | class }}  $table  The table record
	 *
{% endif %}
	 * @return  void
{# endalign #}
	 */
	protected function prepareTable({% if entity.special.publish_up or entity.special.version or entity.special.ordering %}$table{% endif %})
	{
{% if entity.special.publish_up %}
		// Set the publish date to now
		if ({% if entity.special.published %}$table->{{ entity.special.published.name }} == 1 && {% endif %}intval($table->{{ entity.special.publish_up.name }}) == 0)
		{
			$table->{{ entity.special.publish_up.name }} = JFactory::getDate()->toSql();
		}

{% endif %}
{% if entity.special.version %}
		// Increment the {{ entity.name | title }} version number.
		$table->version++;

{% endif %}
{% if entity.special.ordering %}
		// Reorder the {{ entity.name | plural | title }} {% if entity.special.category %} within the category {% endif %} so the new {{ entity.name | singular | title }} is first
		if (empty($table->{{ entity.special.key.name }}))
		{
{% if entity.special.category and entity.special.published %}
			$table->reorder('{{ entity.special.category.name }}=' . (int) $table->{{ entity.special.category.name }} . ' AND {{ entity.special.published.name }}>=0');
{% elseif entity.special.category and not entity.special.published %}
			$table->reorder('{{ entity.special.category.name }}=' . (int) $table->{{ entity.special.category.name }});
{% elseif not entity.special.category and entity.special.published %}
			$table->reorder('{{ entity.special.published.name }}>=0');
{% elseif not entity.special.category and not entity.special.published %}
			$table->reorder();
{% endif %}
		}
{% endif %}

		return;
	}
{# endoverride #}
{# override methods #}{# endoverride #}
}
{% endif %}
