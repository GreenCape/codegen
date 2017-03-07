<?template scope="entity"?>
{% if entity.role != 'lookup' and entity.role != 'map' and entity.special.category %}
<?php
/**
 * Default {{ entity.name | class }} HTML Categories Template
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

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

?>
<div class="categories-list<?php echo $this->pageclass_sfx;?>">

<?php if ($this->params->get('show_page_heading')) : ?>
<h1>
	<?php echo $this->escape($this->params->get('page_heading')); ?>
</h1>
<?php endif; ?>

<?php if ($this->params->get('{{ entity.name | singular | file }}_show_base_description')) : ?>
	<?php if($this->params->get('categories_description')) : ?>
		<?php echo  JHtml::_('content.prepare', $this->params->get('categories_description'), '', 'com_{{ project.name | file }}.categories'); ?>
	<?php  else: ?>
		<?php  if ($this->parent->description) : ?>
			<div class="category-desc">
				<?php  echo JHtml::_('content.prepare', $this->parent->description, '', 'com_{{ project.name | file }}.categories'); ?>
			</div>
		<?php  endif; ?>
	<?php  endif; ?>
<?php endif; ?>

<?php
echo $this->loadTemplate('items');
?>
</div>
{% endif %}}
