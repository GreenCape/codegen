<?template scope="entity"?>
{% if entity.role != 'lookup' and entity.role != 'map' and entity.special.published %}
<?php
/**
 * Default {{ entity.name | class }} HTML Archive Template
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

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
?>
<div class="archive<?php echo $this->pageclass_sfx;?>">
<?php if ($this->params->get('show_page_heading')) : ?>
<h1>
	<?php echo $this->escape($this->params->get('page_heading')); ?>
</h1>
<?php endif; ?>
<form id="adminForm" action="<?php echo JRoute::_('index.php')?>" method="post">
	<fieldset class="filters">
	<legend class="hidelabeltxt"><?php echo JText::_('JGLOBAL_FILTER_LABEL'); ?></legend>
	<div class="filter-search">
		<?php if ($this->params->get('filter_field', '{{ entity.special.title.name }}') != 'hide') : ?>
		<label class="filter-search-lbl" for="filter-search"><?php echo JText::_('COM_{{ project.name | constant }}_{{ entity.name | constant }}_FIELD_'.$this->params->get('filter_field', '{{ entity.special.title.name }}').'_LABEL', '{{ entity.special.title.name }}').'&#160;'; ?></label>
		<input type="text" name="filter-search" id="filter-search" value="<?php echo $this->escape($this->filter); ?>" class="inputbox" onchange="document.getElementById('adminForm').submit();" />
		<?php endif; ?>

		<?php echo $this->form->monthField; ?>
		<?php echo $this->form->yearField; ?>
		<?php echo $this->form->limitField; ?>
		<button type="submit" class="button"><?php echo JText::_('JGLOBAL_FILTER_BUTTON'); ?></button>
	</div>
	<input type="hidden" name="view" value="archive" />
	<input type="hidden" name="option" value="com_{{ project.name | file }}" />
	<input type="hidden" name="limitstart" value="0" />
	</fieldset>

	<?php echo $this->loadTemplate('items'); ?>
</form>
</div>
{% endif %}
