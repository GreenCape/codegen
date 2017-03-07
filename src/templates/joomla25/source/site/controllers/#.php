<?template scope="entity"?>
{% if entity.role != 'lookup' and entity.role != 'map' %}
<?php
/**
 * {{ project.title }} {{ entity.name | title }} Frontend Item Controller
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

/**
 * {{ entity.name | title }} Frontend Controller
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}
 * @since      1.0.0
{# endalign #}
 */
class {{ project.name | class }}Controller{{ entity.name | class }} extends JControllerForm
{
	/**
	 * Controller responsible for item view
	 * @var  string
	 */
	protected $view_item = '{{ entity.name | singular | file }}_form';

	/**
	 * Controller responsible for list view
	 * @var  string
	 */
	protected $view_list = '{{ entity.name | plural | file }}';
{# override properties #}{# endoverride #}
{# override __construct #}{# endoverride #}
{# override execute #}{# endoverride #}
{# override add #}

	/**
	 * Add a new record
	 *
{# align("\t", '  ') #}
	 * @return  void
{# endalign #}
	 */
	public function add()
	{
		if (!parent::add())
		{
			// Redirect to the return page.
			$this->setRedirect($this->getReturnPage());
		}
		return;
	}
{# endoverride #}
{# override allowAdd #}

	/**
	 * Check if user can add a new record
	 *
{# align("\t", '  ') #}
	 * @param	array	$data	  An array of input data
	 *
	 * @return  bool
{# endalign #}
	 */
	protected function allowAdd($data = array())
	{
		$allow  = parent::allowAdd($data);
		$user   = JFactory::getUser();
		$userId = $user->get('id');

		if (!empty($userId))
		{
{% if entity.special.category %}
			$catid = JArrayHelper::getValue($data, '{{ entity.special.category.name }}', JFactory::getApplication()->input->getInt('catid', null), 'int');
			$asset = empty($catid) ? 'com_{{ project.name | file }}' : 'com_{{ project.name | file }}.category.' . $catid;
{% else %}
			$asset  = 'com_{{ project.name | file }}';
{% endif %}
			$allow = $user->authorise('core.create', $asset);
		}

		return $allow;
	}
{# endoverride #}
{# override allowEdit #}

	/**
	 * Check if user can edit an existing record.
	 *
{# align("\t", '  ') #}
	 * @param	array	$data	  An array of input data.
	 * @param	string	$key	  The name of the key for the primary key.
	 *
	 * @return  bool
{# endalign #}
	 */
	protected function allowEdit($data = array(), $key = '{{ entity.special.key.name }}')
	{
		$recordId = JArrayHelper::getValue($data, $key, 0, 'int');
		$user     = JFactory::getUser();

		$allow = parent::allowEdit($data, $key);
		$allow |= $user->authorise('core.edit', $this->option . '.{{ entity.name | singular | file }}.' . $recordId);
{% if entity.special.created_by %}
		$allow |= $user->authorise('core.edit.own', $this->option . '.{{ entity.name | singular | file }}')
			&& $user->get('id') == $this->getOwner($recordId, JArrayHelper::getValue($data, '{{ entity.special.created_by.name }}', 0, 'int'));
{% endif %}

		return $allow;
	}
{% if entity.special.created_by %}

	/**
	 * Get the owner of a record
	 *
	 * If a non-empty user id is provided, it is returned directly.
	 * Otherwise, the method looks for the creator of the record.
	 *
{# align("\t", '  ') #}
	 * @param	int	$recordId	  The record id
	 * @param	int	$ownerId	  The user id to use if record is undefined
	 *
	 * @return  int  The owner id
{# endalign #}
	 */
	protected function getOwner($recordId, $ownerId = null)
	{
		if (!empty($recordId) && empty($ownerId))
		{
			$record  = $this->getModel()->getItem($recordId);
			$ownerId = !empty($record) ? $record->{{ entity.special.created_by.name }} : null;
		}

		return $ownerId;
	}
{% endif %}
{# endoverride #}
{# override cancel #}

	/**
	 * Cancel an edit
	 *
{# align("\t", '  ') #}
	 * @param	string	$key	  The name of the primary key of the URL variable
	 *
	 * @return  void
{# endalign #}
	 */
	public function cancel($key = null)
	{
		parent::cancel($key);
		$this->setRedirect($this->getReturnPage());

		return;
	}
{# endoverride #}
{# override display #}

	/**
	 * Display a view
	 *
{# align("\t", '  ') #}
	 * @param	bool	$cachable	  If true, the view output will be cached
	 * @param	array	$urlparams	  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  {{ project.name | class }}Controller{{ entity.name | class }}  This object to support chaining
{# endalign #}
	 */
	public function display($cachable = true, $urlparams = array())
	{
		JHtml::_('behavior.caption');

		$app   = JFactory::getApplication();
		$input = $app->input;
		$id    = $input->getInt('id', 0);
		$vName = $input->getCmd('view', '{% if entity.special.category %}{{ entity.name | singular | file }}_categories{% endif %}{% if not entity.special.category %}{{ entity.name | plural | file }}{% endif %}');
		$input->set('view', $vName);

		if (empty($urlparams))
		{
			$urlparams = array(
				'catid'            => 'INT',
				'id'               => 'INT',
				'cid'              => 'ARRAY',
				'year'             => 'INT',
				'month'            => 'INT',
				'limit'            => 'UINT',
				'limitstart'       => 'UINT',
				'showall'          => 'INT',
				'return'           => 'BASE64',
				'filter'           => 'STRING',
				'filter_order'     => 'CMD',
				'filter_order_Dir' => 'CMD',
				'filter-search'    => 'STRING',
				'print'            => 'BOOLEAN',
				'lang'             => 'CMD',
			);
		}

		if ($vName == '{{ entity.name | singular | file }}_form' && !$this->checkEditId('com_{{ project.name | file }}.edit.{{ entity.name | singular | file }}', $id))
		{
			// Somehow the person just went to the form - we don't allow that.
			$app->enqueueMessage(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id), 'error');
		}
		else
		{
			parent::display($cachable, $urlparams);
		}
		return $this;
	}
{# endoverride #}
{# override edit #}{# endoverride #}
{# override getModel #}

	/**
	 * Get a model object, loading it if required.
	 *
{# align("\t", '  ') #}
	 * @param	string	$name	  The model name. Optional.
	 * @param	string	$prefix	  The class prefix. Optional.
	 * @param	array	$config	  Configuration array for model. Optional.
	 *
	 * @return  JModel  The model
{# endalign #}
	 */
	public function getModel($name = '{{ entity.name | class }}_Form', $prefix = '{{ project.name | class }}Model', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
{# endoverride #}
{# override getRedirectToItemAppend #}

	/**
	 * Get the URL arguments to append to an item redirect
	 *
{# align("\t", '  ') #}
	 * @param	int	$recordId	  The primary key id for the item
	 * @param	string	$urlVar	  The name of the URL variable for the id
	 *
	 * @return  string  The arguments to append to the redirect URL
{# endalign #}
	 */
	protected function getRedirectToItemAppend($recordId = null, $urlVar = null)
	{
		$append = '';

		if (!empty($recordId))
		{
			$append .= '&' . $urlVar . '=' . $recordId;
		}

		$tmpl = JRequest::getCmd('tmpl');

		if (!empty($tmpl))
		{
			$append .= '&tmpl=' . $tmpl;
		}

		$layout = JRequest::getCmd('layout', 'edit');

		if (!empty($layout))
		{
			$append .= '&layout=' . $layout;
		}

		$itemId = JRequest::getInt('Itemid');

		if (!empty($itemId))
		{
			$append .= '&Itemid=' . $itemId;
		}
{% if entity.special.category %}

		$catId  = JRequest::getInt('{{ entity.special.category.name }}', null, 'get');

		if (!empty($catId))
		{
			$append .= '&{{ entity.special.category.name }}=' . $catId;
		}
{% endif %}

		$return = $this->getReturnPage();

		if (!empty($return))
		{
			$append .= '&return=' . base64_encode($return);
		}

		return $append;
	}
{# endoverride #}
{# override getReturnPage #}

	/**
	 * Get the return URL
	 *
	 * If a "return" variable has been passed in the request
	 *
{# align("\t", '  ') #}
	 * @return  string  The return URL
{# endalign #}
	 */
	protected function getReturnPage()
	{
		$return = JRequest::getVar('return', null, 'default', 'base64');

		if (empty($return) || !JUri::isInternal(base64_decode($return)))
		{
			return JURI::base();
		}
		else
		{
			return base64_decode($return);
		}
	}
{# endoverride #}
{# override postSaveHook #}
{% if entity.special.category %}

	/**
	 * Hook that allows child controller access to model data after the data has been saved
	 *
{# align("\t", '  ') #}
	 * @param	JModel	$model	  The data model object
	 * @param	array	$validData	  The validated data
	 *
	 * @return  void
{# endalign #}
	 */
	protected function postSaveHook(JModel $model, $validData)
	{
		$task = $this->getTask();

		if ($task == 'save')
		{
			$this->setRedirect(JRoute::_('index.php?option=com_{{ project.name | file }}&view={{ entity.name | singular | file }}_category&id=' . $validData['{{ entity.special.category.name }}'], false));
		}

		return;
	}
{% endif %}
{# postSaveHook #}
{# override save #}

	/**
	 * Save a record
	 *
{# align("\t", '  ') #}
	 * @param	string	$key	  The name of the primary key of the URL variable
	 * @param	string	$urlVar	  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *
	 * @return  bool  True if successful, false otherwise
{# endalign #}
	 */
	public function save($key = null, $urlVar = null)
	{
		/** Backend helper for filtering */
		require_once JPATH_ADMINISTRATOR . '/components/com_{{ project.name | file }}/helpers/{{ project.name | file }}.php';

		$result = parent::save($key, $urlVar);
		if ($result)
		{
			$this->setRedirect($this->getReturnPage());
		}
		return $result;
	}
{# endoverride #}
{# override methods #}{# endoverride #}
}
{% endif %}
