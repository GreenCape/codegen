<?template scope="entity"?>
{% if entity.role != 'lookup' and entity.role != 'map' and entity.special.featured %}
<?php
/**
 * Default {{ entity.name | class }} HTML Featured Item Links Template
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

/** @TODO  Adapt this file */

?>
<h3><?php echo JText::_('COM_CONTENT_MORE_ARTICLES'); ?></h3>

<ol>
<?php foreach ($this->link_items as &$item) : ?>
	<li>
		<a href="<?php echo JRoute::_({{ project.name | class }}HelperRoute::get{{ entity.name | class }}Route($item->slug, $item->catslug)); ?>">
			<?php echo $item->title; ?></a>
	</li>
<?php endforeach; ?>
</ol>
{% endif %}
