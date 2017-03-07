<?template scope="entity"?>
{% if entity.role != 'lookup' and entity.role != 'map' and entity.special.category %}
<?php
/**
 * Default {{ entity.name | class }} HTML Category Template
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

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');

?>
<div class="category-list<?php echo $this->pageclass_sfx;?>">

	<?php if ($this->params->get('show_page_heading')) : ?>
	<h1>
		<?php echo $this->escape($this->params->get('page_heading')); ?>
	</h1>
	<?php endif; ?>

	<?php if ($this->params->get('{{ entity.name | singular | file }}_show_category_title', 1) or $this->params->get('page_subheading')) : ?>
	<h2>
		<?php echo $this->escape($this->params->get('page_subheading')); ?>
		<?php if ($this->params->get('{{ entity.name | singular | file }}_show_category_title')) : ?>
			<span class="subheading-category"><?php echo $this->category->title;?></span>
		<?php endif; ?>
	</h2>
	<?php endif; ?>

	<?php if ($this->params->get('{{ entity.name | singular | file }}_show_description', 1) || $this->params->def('{{ entity.name | singular | file }}_show_description_image', 1)) : ?>
	<div class="category-desc">
		<?php if ($this->params->get('{{ entity.name | singular | file }}_show_description_image') && $this->category->getParams()->get('image')) : ?>
			<img src="<?php echo $this->category->getParams()->get('image'); ?>"/>
		<?php endif; ?>
		<?php if ($this->params->get('{{ entity.name | singular | file }}_show_description') && $this->category->description) : ?>
			<?php echo JHtml::_('content.prepare', $this->category->description, '', 'com_{{ project.name | file }}.category'); ?>
		<?php endif; ?>
		<div class="clr"></div>
	</div>
	<?php endif; ?>

	<div class="cat-items">
		<?php echo $this->loadTemplate('{{ entity.name | plural | file }}'); ?>
	</div>

	<?php if (!empty($this->children[$this->category->id])&& $this->maxLevel != 0) : ?>
	<div class="cat-children">
		<h3>
			<?php echo JTEXT::_('JGLOBAL_SUBCATEGORIES'); ?>
		</h3>

		<?php echo $this->loadTemplate('children'); ?>
	</div>
	<?php endif; ?>
</div>
{% endif %}
