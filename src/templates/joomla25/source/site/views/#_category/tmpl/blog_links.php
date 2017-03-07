<?template scope="entity"?>
{% if entity.role != 'lookup' and entity.role != 'map' and entity.special.category %}
<?php
/**
 * Default {{ entity.name | class }} HTML Category Blog Links Template
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

?>
<div class="items-more">

<h3><?php echo JText::_('COM_CONTENT_MORE_ARTICLES'); ?></h3>
<ol>
<?php
	foreach ($this->link_items as &$item) :
?>
	<li>
		<a href="<?php echo JRoute::_({{ project.name | class }}HelperRoute::get{{ entity.name | class }}Route($item->slug, $item->catid)); ?>">
			<?php echo $item->title; ?></a>
	</li>
<?php endforeach; ?>
</ol>
</div>
{% endif %}}
