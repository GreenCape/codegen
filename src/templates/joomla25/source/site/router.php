<?template scope="application"?>
<?php
/**
 * {{ project.title }} Frontend Router
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
 * Build the route for the com_{{ project.name | file }} component
 *
{# align("\t", '  ') #}
 * @param   array  &$query  An array of URL arguments
 *
 * @return  array  The URL arguments to use to assemble the subsequent URL
{# endalign #}
 */
function {{ project.name | class }}BuildRoute(&$query)
{
	$segments = array();

	if (isset($query['view']))
	{
		$segments[] = $query['view'];
		unset($query['view']);
	}
	if (isset($query['task']))
	{
		$segments[] = $query['task'];
		unset($query['task']);
	}
	if (isset($query['id']))
	{
		$segments[] = $query['id'];
		unset($query['id']);
	}

	return $segments;
}

/**
 * Parse the segments of a URL
 *
{# align("\t", '  ') #}
 * @param	array	$segments	  The segments of the URL to parse
 *
 * @return  array  The URL attributes to be used by the application
{# endalign #}
 */
function {{ project.name | class }}ParseRoute($segments)
{
	$vars = array();

	$count = count($segments);

	if ($count > 0)
	{
		$count--;
		$segment = array_shift($segments);
		if (is_numeric($segment))
		{
			$vars['id'] = $segment;
		}
		elseif (strpos($segment, '.') > 0)
		{
			$vars['task'] = $segment;
		}
		else
		{
			$vars['view'] = $segment;
		}
	}

	if ($count > 0)
	{
		$count--;
		$segment = array_shift($segments);
		if (is_numeric($segment))
		{
			$vars['id'] = $segment;
		}
		else
		{
			$vars['task'] = $segment;
		}
	}

	if ($count > 0)
	{
		$count--;
		$segment = array_shift($segments);
		if (is_numeric($segment))
		{
			$vars['id'] = $segment;
		}
	}

	return $vars;
}
{# override functions #}{# endoverride #}
