<?template scope="entity"?>
{% if entity.role != 'lookup' and entity.role != 'map' and entity.special.category %}
<?php
/**
 * {{ project.title }} {{ entity.name | title }} Categories Controller
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

jimport('joomla.application.categories');

/**
 * {{ entity.name | singular | title }} Category List Model
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}
 * @since      1.0.0
{# endalign #}
 */
class {{ project.name | class }}Model{{ entity.name | class }}_Categories extends JModelLegacy
{
	/**
	 * Model context string
	 * @var  string
	 */
	public $_context = 'com_{{ project.name | file }}.{{ entity.name | singular | file }}.categories';

	/**
	 * The category context (allows other extensions to derived from this model)
	 * @var  string
	 */
	protected $_extension = 'com_{{ project.name | file }}';

	/**
	 * Parent category
	 * @var  string
	 */
	private $_parent = null;

	/**
	 * The items
	 * @var  array
	 */
	private $_items = null;
{# override properties #}{# endoverride #}
{# override populateState #}

	/**
	 * Auto-populate the model state
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
{# align("\t", '  ') #}
	 * @return  void
{# endalign #}
	 */
	protected function populateState()
	{
		$app = JFactory::getApplication();
		$this->setState('filter.extension', $this->_extension);

		$parentId = JRequest::getInt('id');
		$this->setState('filter.parentId', $parentId);

		$params = $app->getParams();
		$this->setState('params', $params);

		$this->setState('filter.access', true);
		$this->setState('filter.state', 1);

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
		// Compile the store id.
		$id .= ':' . $this->getState('filter.extension');
		$id .= ':' . $this->getState('filter.state');
		$id .= ':' . $this->getState('filter.access');
		$id .= ':' . $this->getState('filter.parentId');

		return parent::getStoreId($id);
	}
{# endoverride #}
{# override getItems #}

	/**
	 * Redefine the function and add some properties to make the styling more easy
	 *
{# align("\t", '  ') #}
	 * @param	bool	$recursive	  True if you want to return children recursively
	 *
	 * @return  mixed  An array of data items on success, false on failure
{# endalign #}
	 */
	public function getItems($recursive = false)
	{
		if (!count($this->_items))
		{
			$app = JFactory::getApplication();
			$menu = $app->getMenu();
			$active = $menu->getActive();
			$params = new JRegistry;

			if (!empty($active))
			{
				$params->loadString($active->params);
			}

			$options = array();
			$options['countItems'] = $params->get('show_cat_num_items_cat', 1) || !$params->get('{{ entity.name | singular | file }}_{{ entity.name | singular | file }}_show_empty_categories_cat', 0);
			$categories = JCategories::getInstance('{{ project.name | class }}.{{ entity.name | class }}', $options);
			$this->_parent = $categories->get($this->getState('filter.parentId', 'root'));

			if (is_object($this->_parent))
			{
				$this->_items = $this->_parent->getChildren($recursive);
			}
			else
			{
				$this->_items = false;
			}
		}
		return $this->_items;
	}
{# endoverride #}
{# override getParent #}

	/**
	 * Get parent
	 *
{# align("\t", '  ') #}
	 * @return  int  The parent category id
{# endalign #}
	 */
	public function getParent()
	{
		if (!is_object($this->_parent))
		{
			$this->getItems();
		}
		return $this->_parent;
	}
{# endoverride #}
{# override methods #}{# endoverride #}
}
{% endif %}
