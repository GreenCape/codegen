<?template scope="application"?>
<?php
/**
 * {{ project.title }} Admin Entry Point
 *
 * PHP version 5.3
 *
 * @version    {{ project.version }}
 * @package    {{ project.name | class }}
{% for author in project.authors %}
 * @author     {{ author.name }} <{{ author.email | lower }}>
{% endfor %}
 * @copyright  Copyright (C){{ "now" | date('Y') }} {{ project.copyright }}. All rights reserved.
 * @license    {{ project.license }}
 */

// No direct access
defined('_JEXEC') or die;

echo "Hello World! Administration";
