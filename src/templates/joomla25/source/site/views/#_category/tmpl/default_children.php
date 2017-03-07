<?template scope="entity"?>
{% if entity.role != 'lookup' and entity.role != 'map' and entity.special.category %}
<?php
/**
 * Default {{ entity.name | class }} HTML Category Children Template
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

$class = ' class="first"';
?>

<?php if (count($this->children[$this->category->id]) > 0) : ?>
	<ul>
	<?php foreach($this->children[$this->category->id] as $id => $child) : ?>
		<?php
		if ($this->params->get('{{ entity.name | singular | file }}_show_empty_categories') || $child->getNumItems(true) || count($child->getChildren())) :
			if (!isset($this->children[$this->category->id][$id + 1])) :
				$class = ' class="last"';
			endif;
		?>

		<li<?php echo $class; ?>>
			<?php $class = ''; ?>
			<span class="item-title"><a href="<?php echo JRoute::_({{ project.name | class }}HelperRoute::get{{ entity.name | class }}CategoryRoute($child->id));?>">
				<?php echo $this->escape($child->title); ?></a>
			</span>
			<?php if ($this->params->get('{{ entity.name | singular | file }}_show_subcat_desc') == 1) :?>
			<?php if ($child->description) : ?>
				<div class="category-desc">
					<?php echo JHtml::_('content.prepare', $child->description, '', 'com_{{ project.name | file }}.category'); ?>
				</div>
			<?php endif; ?>
			<?php endif; ?>
			<?php if ( $this->params->get('show_cat_num_items', 1)) : ?>
			<dl>
				<dt>
					<?php echo JText::_('COM_CONTENT_NUM_ITEMS') ; ?>
				</dt>
				<dd>
					<?php echo $child->getNumItems(true); ?>
				</dd>
			</dl>
			<?php endif ; ?>

			<?php if (count($child->getChildren()) > 0 ) :
				$this->children[$child->id] = $child->getChildren();
				$this->category = $child;
				$this->maxLevel--;
				if ($this->maxLevel != 0) :
					echo $this->loadTemplate('children');
				endif;
				$this->category = $child->getParent();
				$this->maxLevel++;
			endif; ?>
			</li>
		<?php endif; ?>
	<?php endforeach; ?>
	</ul>
<?php endif; ?>
{% endif %}
