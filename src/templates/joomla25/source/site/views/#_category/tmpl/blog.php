<?template scope="entity"?>
{% if entity.role != 'lookup' and entity.role != 'map' and entity.special.category %}
<?php
/**
 * Default {{ entity.name | class }} HTML Category Blog Template
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
<div class="blog<?php echo $this->pageclass_sfx;?>">
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

	<?php $leadingcount = 0; ?>
	<?php if (!empty($this->lead_items)) : ?>
	<div class="items-leading">
		<?php foreach ($this->lead_items as &$item) : ?>
			<div class="leading-<?php echo $leadingcount, $item->state == 0 ? ' system-unpublished' : ''; ?>">
				<?php
					$this->item = &$item;
					echo $this->loadTemplate('item');
				?>
			</div>
			<?php $leadingcount++; ?>
		<?php endforeach; ?>
	</div>
	<?php endif; ?>

	<?php
    $introcount = count($this->intro_items);
    $counter    = 0;
	?>
	<?php if (!empty($this->intro_items)) : ?>

	<?php foreach ($this->intro_items as $key => &$item) : ?>
		<?php
		$key      = $key - $leadingcount + 1;
		$rowcount = (((int) $key - 1) % (int) $this->columns) + 1;
		$row      = $counter / $this->columns;
		?>
		<?php if ($rowcount == 1) : ?>
		<div class="items-row cols-<?php echo (int) $this->columns, ' ', 'row-' . $row; ?>">
		<?php endif; ?>

		<div class="item column-<?php echo $rowcount, $item->state == 0 ? ' system-unpublished' : ''; ?>">
			<?php
			$this->item = &$item;
			echo $this->loadTemplate('item');
			?>
		</div>
		<?php $counter++; ?>
		<?php if (($rowcount == $this->columns) || ($counter == $introcount)) : ?>
		<span class="row-separator"></span>
		</div>
		<?php endif; ?>

	<?php endforeach; ?>

	<?php endif; ?>

	<?php if (!empty($this->link_items)) : ?>
	<?php echo $this->loadTemplate('links'); ?>
	<?php endif; ?>

	<?php if (!empty($this->children[$this->category->id])&& $this->maxLevel != 0) : ?>
	<div class="cat-children">
		<h3>
			<?php echo JTEXT::_('JGLOBAL_SUBCATEGORIES'); ?>
		</h3>
		<?php echo $this->loadTemplate('children'); ?>
	</div>
	<?php endif; ?>

	<?php if (($this->params->def('show_pagination', 1) == 1  || ($this->params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) : ?>
	<div class="pagination">
		<?php if ($this->params->def('show_pagination_results', 1)) : ?>
		<p class="counter">
			<?php echo $this->pagination->getPagesCounter(); ?>
		</p>
		<?php endif; ?>
		<?php echo $this->pagination->getPagesLinks(); ?>
	</div>
	<?php  endif; ?>

</div>
{% endif %}}
