<?template scope="application"?>
<?php
/**
 * {{ project.title }} Admin Controller
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
 * {{ project.title }} Admin Controller
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}
 * @since      1.0.0
{# endalign #}
 */
class {{ project.name | class }}AdminController extends JController
{
	/**
	 * The default view
	 * @var  string
	 */
	protected $default_view = '{% for entity in project.entities %}{% if entity.role == 'main' %}{{ entity.name | plural | file }}{% endif %}{% endfor %}';
{# override properties #}{# endoverride #}
{# override __construct #}{# endoverride #}
{# override display #}

	/**
	 * Display a view
	 *
{# align("\t", '  ') #}
	 * @param	bool	$cachable	  If true, the view output will be cached. Default is false.
	 * @param	array	$urlparams	  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  {{ project.name | class }}AdminController  This object to support chaining
{# endalign #}
   */
	public function display($cachable = false, $urlparams = array())
	{
		require_once __DIR__ . '/helpers/{{ project.name | file }}.php';

		$input = JFactory::getApplication()->input;

		// Load the submenu.
		{{ project.name | class }}AdminHelper::addSubmenu($input->getCmd('view', '{% for entity in project.entities %}{% if entity.role == 'main' %}{{ entity.name | plural | file }}{% endif %}{% endfor %}'));

		$view   = $input->getCmd('view', '{% for entity in project.entities %}{% if entity.role == 'main' %}{{ entity.name | plural | file }}{% endif %}{% endfor %}');
		$layout = $input->getCmd('layout', 'default');
		$id     = $input->getInt('id', null);

		if ($layout == 'edit' && !$this->checkEditId('com_{{ project.name | file }}.edit.' . $view, $id))
		{
			$this->setMessage(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_{{ project.name | file }}&view={% for entity in project.entities %}{% if entity.role == 'main' %}{{ entity.name | plural | file }}{% endif %}{% endfor %}', false));

			return false;
		}

		parent::display($cachable, $urlparams);

		return $this;
	}
{# endoverride #}
{# override methods #}{# endoverride #}
}
