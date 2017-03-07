<?template scope="entity"?>
{% if entity.role != 'lookup' and entity.role != 'map' and entity.special.published %}
<?php
/**
 * Default {{ entity.name | class }} HTML Archive Item Template
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
$params = &$this->params;
?>

<ul id="archive-items">
<?php foreach ($this->items as $i => $item) : ?>
	<li class="row<?php echo $i % 2; ?>">

		<h2>
		<?php if ($params->get('{{ entity.name | singular | file }}_link_titles')): ?>
			<a href="<?php echo JRoute::_({{ project.name | class }}HelperRoute::get{{ entity.name | class }}Route($item->slug, $item->catslug)); ?>">
				<?php echo $this->escape($item->title); ?></a>
		<?php else: ?>
				<?php echo $this->escape($item->title); ?>
		<?php endif; ?>
		</h2>

<?php if (($params->get('{{ entity.name | singular | file }}_show_author')) or ($params->get('{{ entity.name | singular | file }}_show_parent_category')) or ($params->get('{{ entity.name | singular | file }}_show_category')) or ($params->get('{{ entity.name | singular | file }}_show_create_date')) or ($params->get('{{ entity.name | singular | file }}_show_modify_date')) or ($params->get('{{ entity.name | singular | file }}_show_publish_date'))  or ($params->get('{{ entity.name | singular | file }}_show_hits'))) : ?>
<dl class="article-info">
<dt class="article-info-term"><?php echo JText::_('COM_CONTENT_ARTICLE_INFO'); ?></dt>
<?php endif; ?>
<?php if ($params->get('{{ entity.name | singular | file }}_show_parent_category')) : ?>
		<dd class="parent-category-name">
			<?php    $title = $this->escape($item->parent_title);
					$url = '<a href="'.JRoute::_({{ project.name | class }}HelperRoute::get{{ entity.name | class }}CategoryRoute($item->parent_slug)).'">'.$title.'</a>';?>
			<?php if ($params->get('{{ entity.name | singular | file }}_link_parent_category') && $item->parent_slug) : ?>
				<?php echo JText::sprintf('COM_CONTENT_PARENT', $url); ?>
				<?php else : ?>
				<?php echo JText::sprintf('COM_CONTENT_PARENT', $title); ?>
			<?php endif; ?>
		</dd>
<?php endif; ?>

<?php if ($params->get('{{ entity.name | singular | file }}_show_category')) : ?>
		<dd class="category-name">
			<?php    $title = $this->escape($item->category_title);
					$url = '<a href="' . JRoute::_({{ project.name | class }}HelperRoute::get{{ entity.name | class }}CategoryRoute($item->catslug)) . '">' . $title . '</a>'; ?>
			<?php if ($params->get('{{ entity.name | singular | file }}_link_category') && $item->catslug) : ?>
				<?php echo JText::sprintf('COM_CONTENT_CATEGORY', $url); ?>
				<?php else : ?>
				<?php echo JText::sprintf('COM_CONTENT_CATEGORY', $title); ?>
			<?php endif; ?>
		</dd>
<?php endif; ?>
<?php if ($params->get('{{ entity.name | singular | file }}_show_create_date')) : ?>
		<dd class="create">
		<?php echo JText::sprintf('COM_CONTENT_CREATED_DATE_ON', JHtml::_('date', $item->created, JText::_('DATE_FORMAT_LC2'))); ?>
		</dd>
<?php endif; ?>
<?php if ($params->get('{{ entity.name | singular | file }}_show_modify_date')) : ?>
		<dd class="modified">
		<?php echo JText::sprintf('COM_CONTENT_LAST_UPDATED', JHtml::_('date', $item->modified, JText::_('DATE_FORMAT_LC2'))); ?>
		</dd>
<?php endif; ?>
<?php if ($params->get('{{ entity.name | singular | file }}_show_publish_date')) : ?>
		<dd class="published">
		<?php echo JText::sprintf('COM_CONTENT_PUBLISHED_DATE_ON', JHtml::_('date', $item->publish_up, JText::_('DATE_FORMAT_LC2'))); ?>
		</dd>
<?php endif; ?>
<?php if ($params->get('{{ entity.name | singular | file }}_show_author') && !empty($item->author )) : ?>
	<dd class="createdby">
		<?php $author =  $item->author; ?>
		<?php $author = ($item->created_by_alias ? $item->created_by_alias : $author);?>

			<?php if (!empty($item->contactid ) &&  $params->get('{{ entity.name | singular | file }}_link_author') == true):?>
				<?php     echo JText::sprintf('COM_CONTENT_WRITTEN_BY' ,
				 JHtml::_('link', JRoute::_('index.php?option=com_contact&view=contact&id='.$item->contactid), $author)); ?>

			<?php else :?>
				<?php echo JText::sprintf('COM_CONTENT_WRITTEN_BY', $author); ?>
			<?php endif; ?>
	</dd>
<?php endif; ?>
<?php if ($params->get('{{ entity.name | singular | file }}_show_hits')) : ?>
		<dd class="hits">
		<?php echo JText::sprintf('COM_CONTENT_ARTICLE_HITS', $item->hits); ?>
		</dd>
<?php endif; ?>
<?php if (($params->get('{{ entity.name | singular | file }}_show_author')) or ($params->get('{{ entity.name | singular | file }}_show_category')) or ($params->get('{{ entity.name | singular | file }}_show_create_date')) or ($params->get('{{ entity.name | singular | file }}_show_modify_date')) or ($params->get('{{ entity.name | singular | file }}_show_publish_date'))  or ($params->get('{{ entity.name | singular | file }}_show_hits'))) :?>
	</dl>
<?php endif; ?>

<?php if ($params->get('{{ entity.name | singular | file }}_show_intro')) :?>
	<div class="intro">
		<?php echo JHtml::_('string.truncate', $item->introtext, $params->get('introtext_limit')); ?>
	</div>
<?php endif; ?>
	</li>
<?php endforeach; ?>
</ul>

<div class="pagination">
	<p class="counter">
		<?php echo $this->pagination->getPagesCounter(); ?>
	</p>
	<?php echo $this->pagination->getPagesLinks(); ?>
</div>
{% endif %}}
