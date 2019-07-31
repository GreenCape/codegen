<?template scope="entity"?>
{% if entity.role == 'main' and entity.special.featured and entity.special.key %}
<?php
/**
 * {{ project.title }} Featured {{ entity.name | title }} Admin List Controller
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
 * Featured {{ entity.name | title }} List Controller Class.
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}
 * @since      1.0.0
{# endalign #}
 */
class {{ project.name | class }}AdminControllerFeatured extends {{ project.name | class }}Controller{{ entity.name | plural | class }}
{
{# override properties #}{# endoverride #}
{# override delete #}

	/**
	 * Remove an item
	 *
{# align("\t", '  ') #}
	 * @return  void
{# endalign #}
   */
	public function delete()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$user = JFactory::getUser();
		$ids  = JRequest::getVar('cid', array(), '', 'array');

		foreach ($ids as $i => $id)
		{
			if (!$user->authorise('core.delete', $this->option . '.{{ entity.name | singular | file }}.' . (int) $id))
			{
				unset($ids[$i]);
				JError::raiseNotice(403, JText::_('JERROR_CORE_DELETE_NOT_PERMITTED'));
			}
		}

		if (empty($ids))
		{
			JError::raiseWarning(500, JText::_('JERROR_NO_ITEMS_SELECTED'));
		}
		else
		{
			$model = $this->getModel();

			if (!$model->featured($ids, 0)) {
				JError::raiseWarning(500, $model->getError());
			}
		}
		$this->setRedirect('index.php?option=' . $this->option . '&view=featured');
		return;
	}
{# endoverride #}
{# override publish #}

	/**
	 * Publish a list of articles.
	 *
{# align("\t", '  ') #}
	 * @return  void
{# endalign #}
   */
	public function publish()
	{
		parent::publish();
		$this->setRedirect('index.php?option=' . $this->option . '&view=featured');
		return;
	}
{# endoverride #}
{# override getModel #}

	/**
	 * Get a model object, loading it if required.
	 *
{# align("\t", '  ') #}
	 * @param	string	$name	  The model name. Optional
	 * @param	string	$prefix	  The class prefix. Optional
	 * @param	array	$config	  *
	 * @return  JModel  The model
{# endalign #}
	 */
	public function getModel($name = 'Feature', $prefix = '{{ project.name | class }}Model', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}
{# endoverride #}
{# override methods #}{# endoverride #}
}
{% endif %}
