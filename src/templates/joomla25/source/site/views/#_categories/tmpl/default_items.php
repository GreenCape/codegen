<?template scope="entity"?>
{% if entity.role != 'lookup' and entity.role != 'map' and entity.special.category %}
<?php
/**
 * Default {{ entity.name | class }} HTML Categories Items Template
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
 */

// No direct access
defined('_JEXEC') or die;

$class = ' class="first"';
?>
<?php if (count($this->items[$this->parent->id]) > 0 && $this->{{ entity.name | singular | file }}_maxLevelcat != 0) : ?>
<ul>
<?php foreach($this->items[$this->parent->id] as $id => $item) : ?>
	<?php if ($this->params->get('{{ entity.name | singular | file }}_{{ entity.name | singular | file }}_show_empty_categories_cat') || $item->numitems || count($item->getChildren())) : ?>
	<?php
	if (!isset($this->items[$this->parent->id][$id + 1]))
	{
		$class = ' class="last"';
	}
	?>
	<li<?php echo $class; ?>>
		<?php $class = ''; ?>
		<span class="item-title"><a href="<?php echo JRoute::_({{ project.name | class }}HelperRoute::get{{ entity.name | class }}CategoryRoute($item->id));?>">
			<?php echo $this->escape($item->title); ?></a>
		</span>
		<?php if ($this->params->get('{{ entity.name | singular | file }}_{{ entity.name | singular | file }}_show_subcat_desc_cat') == 1) :?>
		<?php if ($item->description) : ?>
			<div class="category-desc">
				<?php echo JHtml::_('content.prepare', $item->description, '', 'com_{{ project.name | file }}.categories'); ?>
			</div>
		<?php endif; ?>
		<?php endif; ?>
		<?php if ($this->params->get('show_cat_num_items_cat') == 1) :?>
			<dl><dt>
				<?php echo JText::_('COM_CONTENT_NUM_ITEMS'); ?></dt>
				<dd><?php echo $item->numitems; ?></dd>
			</dl>
		<?php endif; ?>

		<?php if (count($item->getChildren()) > 0) :
			$this->items[$item->id] = $item->getChildren();
			$this->parent = $item;
			$this->{{ entity.name | singular | file }}_maxLevelcat--;
			echo $this->loadTemplate('items');
			$this->parent = $item->getParent();
			$this->{{ entity.name | singular | file }}_maxLevelcat++;
		endif; ?>

	</li>
	<?php endif; ?>
<?php endforeach; ?>
</ul>
<?php endif; ?>
{% endif %}}
