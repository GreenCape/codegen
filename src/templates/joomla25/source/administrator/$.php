<?template scope="application"?>
<?php
/**
 * {{ project.title }} Admin Entry Point
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
 *
 * @startuml{uml-seq-admin_{{ project.name | file }}.svg}
 *
 * participant {{ project.name | file }}.php as script
 * box "Joomla Core"
 *     participant JUser
 *     participant JController
 * end box
 * participant "{{ project.name | class }}Admin*" as controller
 *
 * [-> script
 * activate script
 *
 * script -> JUser : authorise()
 * activate JUser
 * note over JUser: Check if user has <b>core.manage</b>\nprivileges for <b>com_{{ project.name | file }}</b>
 * script <-- JUser : Access granted\nor declined
 * deactivate JUser
 *
 * alt Access granted
 *
 *     script -> JController : getInstance()
 *     activate JController
 *     create controller
 *     JController -> controller : «create»
 *     note over controller: The actual controller class\ndepends on requested task
 *     script <- JController : controller
 *     deactivate JController
 *     script -> controller : execute()
 *     activate controller
 *     controller -> controller : <i><task></i>()
 *     activate controller
 *     |||
 *     deactivate controller
 *     script <-- controller
 *     deactivate controller
 *     script -> controller : redirect()
 *     script <-- controller
 *
 * else Access declined
 *
 *     |||
 *
 * end
 * [<-- script
 * deactivate script
 * deactivate controller
 * @enduml
 */

// No direct access
defined('_JEXEC') or die;

if (!JFactory::getUser()->authorise('core.manage', 'com_{{ project.name | file }}'))
{
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 403);
}

// Joomla should manage this, really
JLoader::register('JController', JPATH_PLATFORM . '/joomla/application/component/controller.php');
JLoader::register('JControllerAdmin', JPATH_PLATFORM . '/joomla/application/component/controlleradmin.php');
JLoader::register('JControllerForm', JPATH_PLATFORM . '/joomla/application/component/controllerform.php');
JLoader::register('JModel', JPATH_PLATFORM . '/joomla/application/component/model.php');
JLoader::register('JModelAdmin', JPATH_PLATFORM . '/joomla/application/component/modeladmin.php');
JLoader::register('JModelForm', JPATH_PLATFORM . '/joomla/application/component/modelform.php');
JLoader::register('JModelItem', JPATH_PLATFORM . '/joomla/application/component/modelitem.php');
JLoader::register('JModelList', JPATH_PLATFORM . '/joomla/application/component/modellist.php');
JLoader::register('JView', JPATH_PLATFORM . '/joomla/application/component/view.php');
JLoader::register('JFormField', JPATH_PLATFORM . '/joomla/form/field.php');
JLoader::register('JFormFieldList', JPATH_PLATFORM . '/joomla/form/fields/list.php');

// Re-use standard language strings
JFactory::getLanguage()->load('com_content');

$controller = JController::getInstance('{{ project.name | class }}Admin');
$controller->execute(JFactory::getApplication()->input->getCmd('task', null));
$controller->redirect();
