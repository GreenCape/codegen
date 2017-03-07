<?template scope="application"?>
<?php
/**
 * {{ project.title }} Frontend Entry Point
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

defined('_JEXEC') or die;

// Include dependencies
jimport('joomla.application.component.controller');

require_once JPATH_COMPONENT . '/helpers/route.php';
require_once JPATH_COMPONENT . '/helpers/query.php';

JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_{{ project.name | file }}/tables');

$app = JFactory::getApplication();

// Re-use standard language strings
JFactory::getLanguage()->load('com_content');

// Import CSS
JFactory::getDocument()->addStyleSheet('components/com_{{ project.name | file }}/assets/css/{{ project.name | file }}.css');

$view = $app->input->get('view', null, 'cmd');
$task = $app->input->get('task', 'display', 'cmd');
if (strpos($task, '.') > 0)
{
	list($view, $task) = explode('.', $task, 2);
}

$controllerName = '{{ project.name | class }}Controller';
$controllerFile = JPATH_COMPONENT . '/controllers/' . $view . '.php';

if (file_exists($controllerFile))
{
	require_once $controllerFile;
	$controllerName .= ucfirst($view);
}
else
{
	require_once dirname(__FILE__) . '/controller.php';
}

// Execute the task.
$controller = new $controllerName;
$controller->execute($task);
$controller->redirect();
