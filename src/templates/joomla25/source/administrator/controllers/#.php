<?template scope="entity"?>
{% if entity.special.key %}
<?php
/**
 * {{ project.title }} {{ entity.name | title }} Admin Controller
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
 * {{ entity.name | title }} controller class.
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}
 * @since      1.0.0
{# endalign #}
 */
class {{ project.name | class }}AdminController{{ entity.name | class }} extends JControllerForm
{
	/**
	 * The context for storing internal data, e.g. record.
	 * @var  string
	 */
	protected $context = '{{ entity.name | singular | class }}';

	/**
	 * The prefix to use with controller messages.
	 * @var  string
	 */
	protected $text_prefix = 'COM_{{ project.name | constant }}_{{ entity.name | constant }}';

	/**
	 * Default view for single items
	 * @var  string
	 */
	protected $view_item = '{{ entity.name | singular | file }}';

	/**
	 * Default view for list of items
	 * @var  string
	 */
	protected $view_list = '{{ entity.name | plural | file }}';
{# override properties #}{# endoverride #}
{# override __construct #}

	/**
	 * Class constructor.
	 *
{# align("\t", '  ') #}
	 * @param	array	$config	  A named array of configuration variables.
{# endalign #}
	 */
	public function __construct($config = array())
	{
		$this->option = 'com_{{ project.name | file }}';
{% if entity.special.featured %}

		if (JFactory::getApplication()->input->getCmd('return', null) == 'featured')
		{
			$this->view_list = 'featured';
			$this->view_item = '{{ entity.name | singular | file }}&return=featured';
		}
{% endif %}

		parent::__construct($config);
		return;
	}
{# endoverride #}
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
			$this->setRedirect($this->getReturnPage());
		}
	}
{# endoverride #}
{# override cancel #}

	/**
	 * Cancel an edit
	 *
{# align("\t", '  ') #}
	 * @param	string	$key	  The name of the primary key of the URL variable.
	 *
	 * @return  void
{# endalign #}
   */
	public function cancel($key = null)
	{
		parent::cancel($key);
		$this->setRedirect($this->getReturnPage());
	}
{# endoverride #}
{# override edit #}

	/**
	 * Edit an existing record
	 *
{# align("\t", '  ') #}
	 * @param	string	$key	  The name of the primary key of the URL variable.
	 * @param	string	$urlVar	  The name of the URL variable if different from the primary key
	 *			(sometimes required to avoid router collisions).
	 *
	 * @return  bool  True on success, false otherwise.
{# endalign #}
	 */
	public function edit($key = null, $urlVar = null)
	{
		$result = parent::edit($key, $urlVar);

		return $result;
	}
{# endoverride #}
{# override getModel #}

	/**
	 * Get a model object, loading it if required.
	 *
{# align("\t", '  ') #}
	 * @param	string	$name	  The model name. Optional.
	 * @param	string	$prefix	  The class prefix. Optional.
	 * @param	array	$config	  Configuration options
	 *
	 * @return  JModel  The model
{# endalign #}
	 */
	public function getModel($name = '{{ entity.name | class }}', $prefix = '{{ project.name | class }}AdminModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}
{# endoverride #}
{# override allowAdd #}
{% if entity.special.category %}

	/**
	 * Check if user can add a new record.
	 *
{# align("\t", '  ') #}
	 * @param	array	$data	  An array of input data.
	 *
	 * @return  bool
{# endalign #}
	 */
	protected function allowAdd($data = array())
	{
		// Initialise variables.
		$categoryId = JArrayHelper::getValue($data, '{{ entity.special.category.name }}', JRequest::getInt('filter_category_id'), 'int');
		$allow      = null;

		if (!empty($categoryId))
		{
			$allow  = JFactory::getUser()->authorise('core.create', $this->option . '.category.' . $categoryId);
		}

		if (is_null($allow))
		{
			$allow = parent::allowAdd($data);
		}
		return $allow;
	}
{% endif %}
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
	 * @param	int	$recordId	  The record id
	 * @param	int	$ownerId	  The user id to use if record is undefined
	 *
	 * @return  int  The owner id
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
{# override batch #}

	/**
	 * Run batch operations
	 *
{# align("\t", '  ') #}
	 * @param	JModel	$model	  The model
	 *
	 * @return  bool  True on success
{# endalign #}
	 */
	public function batch($model = null)
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		if (is_null($model))
		{
			$model = $this->getModel('{{ entity.name | class }}', '{{ project.name | class }}AdminModel', array());
		}
		$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view={{ entity.name | plural | file }}' . $this->getRedirectToListAppend(), false));

		return parent::batch($model);
	}
{# endoverride #}
{# override getRedirectToListAppend #}

	/**
	 * Get the URL arguments to append to a list redirect
	 *
{# align("\t", '  ') #}
	 * @return  string  The arguments to append to the redirect URL
{# endalign #}
	 */
	protected function getRedirectToListAppend()
	{
		return $this->getRedirectToItemAppend();
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
		$return = JFactory::getApplication()->input->getBase64('return', null);

		if (empty($return) || !JUri::isInternal(base64_decode($return)))
		{
			return JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list, false);
		}
		else
		{
			return base64_decode($return);
		}
	}
{# endoverride #}
{# override methods #}{# endoverride #}
}
{% endif %}
