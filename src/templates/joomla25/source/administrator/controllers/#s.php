<?template scope="entity"?>
{% if entity.special.key %}
<?php
/**
 * {{ project.title }} {{ entity.name | title }} Admin List Controller
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
 * {{ entity.name | title }} list controller class.
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}
 * @since      1.0.0
{# endalign #}
 */
class {{ project.name | class }}AdminController{{ entity.name | plural | class }} extends JControllerAdmin
{
	/**
	 * The context for storing internal data, e.g. record.
	 * @var  string
	 */
	protected $context = '{{ entity.name | plural | class }}';

	/**
	 * The prefix to use with controller messages.
	 * @var  string
	 */
	protected $text_prefix = 'COM_{{ project.name | constant }}_{{ entity.name | constant }}';
{# override properties #}{# endoverride #}
{# override __construct #}

	/**
	 * Constructor
	 *
{# align("\t", '  ') #}
	 * @param	array	$config	  An optional associative array of configuration settings.
{# endalign #}
	 */
	public function __construct($config = array())
	{
		$this->option = 'com_{{ project.name | file }}';
{% if entity.special.featured %}

		if (JFactory::getApplication()->input->getCmd('view', null) == 'featured') {
			$this->view_list = 'featured';
		}
{% endif %}

		parent::__construct($config);

		// Task mapping
		$this->registerTask('apply', 'save');
{% if entity.special.featured %}
		$this->registerTask('unfeatured', 'featured');
{% endif %}
{% if entity.special.sticky %}
		$this->registerTask('sticky_unpublish', 'sticky_publish');
{% endif %}

		return;
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
{# override delete #}
{% if entity.role == 'lookup' %}

	/**
	 * Remove items.
	 *
{# align("\t", '  ') #}
	 * @return  void
{# endalign #}
	 */
	public function delete()
	{
		JSession::checkToken('get') or die(JText::_('JINVALID_TOKEN'));

		$cid = JRequest::getVar('cid', array(), '', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_($this->text_prefix . '_NO_ITEM_SELECTED'));
		}
		else
		{
			$model = $this->getModel('{{ entity.name | class }}', '{{ project.name | class }}AdminModel');

			JArrayHelper::toInteger($cid);

			if ($model->delete($cid))
			{
				$this->setMessage(JText::plural($this->text_prefix . '_N_ITEMS_DELETED', count($cid)));
			}
			else
			{
				$this->setMessage($model->getError());
			}
		}

		$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list . $this->getRedirectToListAppend(), false));
		return;
	}
{% endif %}
{# endoverride #}
{% if entity.special.featured %}
{# override featured #}

	/**
	 * Toggle the featured setting of a list of {{ entity.name | plural | title }}.
	 *
{# align("\t", '  ') #}
	 * @return  void
{# endalign #}
   */
	public function featured()
	{
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Initialise variables.
		$user   = JFactory::getUser();
		$ids    = JRequest::getVar('cid', array(), '', 'array');
		$values = array('featured' => 1, 'unfeatured' => 0);
		$task   = $this->getTask();
		$value  = JArrayHelper::getValue($values, $task, 0, 'int');
		$app    = JFactory::getApplication();

		// Access checks.
		$removed = 0;
		foreach ($ids as $i => $id) {
			if (!$user->authorise('core.edit.state', 'com_{{ project.name | file }}.{{ entity.name | singular | file }}.' . (int) $id)) {
				// Prune items that you can't change.
				unset($ids[$i]);
				$removed++;
			}
		}
		if ($removed > 0) {
			$app->enqueueMessage(JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'), 'warning');
		}
		if (empty($ids)) {
			$app->enqueueMessage(JText::_('JERROR_NO_ITEMS_SELECTED'), 'warning');
		} else {
			$model = $this->getModel();
			if (!$model->featured($ids, $value)) {
				$app->enqueueMessage($model->getError(), 'error');
			}
		}
		$this->setRedirect('index.php?option=' . $this->option . '&view={{ entity.name | plural | file }}');
		return;
	}
{# endoverride #}
{% endif %}
{% if entity.special.sticky %}
{# override stick #}

	/**
	 * Toggle the sticky setting of a list of {{ entity.name | plural | title }}.
	 *
{# align("\t", '  ') #}
	 * @return  void
{# endalign #}
   */
	public function stick()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$user  = JFactory::getUser();
		$ids  = JRequest::getVar('cid', array(), '', 'array');
		$values  = array('sticky_publish' => 1, 'sticky_unpublish' => 0);
		$task  = $this->getTask();
		$value  = JArrayHelper::getValue($values, $task, 0, 'int');

		if (empty($ids)) {
			JError::raiseWarning(500, JText::_('COM_{{ project.name | constant }}_{{ entity.name | constant }}_NO_ITEM_SELECTED'));
		} else {
			$model  = $this->getModel();

			if (!$model->stick($ids, $value))
			{
				JError::raiseWarning(500, $model->getError());
			}
			else
			{
				if ($value == 1)
				{
					$ntext = 'COM_{{ project.name | constant }}_{{ entity.name | constant }}_N_ITEMS__STUCK';
				}
				else
				{
					$ntext = 'COM_{{ project.name | constant }}_{{ entity.name | constant }}_N_ITEMS__UNSTUCK';
				}
				$this->setMessage(JText::plural($ntext, count($ids)));
			}
		}
		$this->setRedirect('index.php?option=' . $this->option . '&view={{ entity.name | plural | file }}');
		return;
	}
{# endoverride #}
{% endif %}
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
{# override getRedirectToItemAppend #}

	/**
	 * Get the URL arguments to append to an item redirect
	 *
{# align("\t", '  ') #}
	 * @param	integer	$recordId	  The primary key id for the item
	 * @param	string	$urlVar	  The name of the URL variable for the id
	 *
	 * @return  string  The arguments to append to the redirect URL
{# endalign #}
	 */
	protected function getRedirectToItemAppend($recordId = null, $urlVar = 'id')
	{
		$input  = JFactory::getApplication()->input;
		$tmpl   = $input->getCmd('tmpl', null);
		$layout = $input->getCmd('layout', 'edit');
		$append = '';

		if (!empty($tmpl))
		{
			$append .= '&tmpl=' . $tmpl;
		}

		if (!empty($layout))
		{
			$append .= '&layout=' . $layout;
		}

		if (!empty($recordId))
		{
			$append .= '&' . $urlVar . '=' . $recordId;
		}

		return $append;
	}
{# endoverride #}
{# override methods #}{# endoverride #}
}
{% endif %}
