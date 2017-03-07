<?template scope="application"?>
<?php
/**
 * {{ project.title }} Installation Script
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
 * {{ project.title }} Installer
 *
{# align("\t", '  ') #}
 * @version    Release: {{ project.version }}
 * @package    {{ project.name | class }}
 * @since      1.0.0
{# endalign #}
 */
class Com_{{ project.name | class }}InstallerScript
{
{# override properties #}{# endoverride #}
{# override install #}
	/**
	 * Run when the component is installed
	 *
{# align("\t", '  ') #}
	 * @return  bool  Success
{# endalign #}
	 */
	public function install()
	{
		return true;
	}
{# endoverride #}
{# override uninstall #}

	/**
	 * Run when the component is uninstalled.
	 *
{# align("\t", '  ') #}
	 * @return  void
{# endalign #}
   */
	public function uninstall()
	{
		return;
	}
{# endoverride #}
{# override update #}

	/**
	 * Run when the component is updated
	 *
{# align("\t", '  ') #}
	 * @return  bool  Success
{# endalign #}
	 */
	public function update()
	{
		return true;
	}
{# endoverride #}
{# override preflight #}

	/**
	 * Run before installation or upgrade run
	 *
{# align("\t", '  ') #}
	 * @param	string	$type	  discover_install  - install unregistered extensions that have been discovered
	 * 			install  - standard install
	 * 			update  - update
	 *
	 * @return  bool  Success
{# endalign #}
	 */
	public function preflight($type)
	{
		$version = new JVersion;
		$errors  = array();

		if (version_compare($version->getShortVersion(), '2.5.0', 'lt'))
		{
			$errors[] = 'Joomla! 2.5 is required for {{ project.title }}';
		}
		if (!empty($errors))
		{
			$app = JFactory::getApplication();
			foreach ($errors as $error)
			{
				$app->enqueueMessage($error, 'error');
			}
			return false;
		}

		switch ($type)
		{
			case 'update':
				break;

			case 'discover_install':
			case 'install':
			default:
				break;
		}
		return true;
	}
{# endoverride #}
{# override postflight #}

	/**
	 * Run after installation or upgrade run
	 *
{# align("\t", '  ') #}
	 * @param	string	$type	  discover_install  - install unregistered extensions that have been discovered
	 * 			install  - standard install
	 * 			update  - update
	 *
	 * @return  void
{# endalign #}
   */
	public function postflight($type)
	{
		switch ($type)
		{
			case 'update':
				break;

			case 'discover_install':
			case 'install':
			default:
				$db    = JFactory::getDbo();
				$rule  = '{"core.admin":{"7":1},"core.manage":{"6":1},"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[],"core.edit.own":[]}';
				$query = "UPDATE #__assets SET rules = '" . $db->escape($rule) . "' WHERE name = 'com_{{ project.name | file }}'";
				$db->setQuery($query);
				$db->execute();
				break;
		}
		return;
	}
{# endoverride #}
{# override methods #}{# endoverride #}
}
