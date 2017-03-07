<?template scope="entity"?>
{% if entity.role != 'lookup' and entity.role != 'map' and entity.special.category %}
<?php
/**
 * Default {{ entity.name | class }} HTML Category Items Template
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

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

JHtml::_('behavior.tooltip');
JHtml::_('behavior.framework');

$params    = $this->item->params;
$n         = count($this->items);
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
$user      = JFactory::getUser();
?>

<?php if (empty($this->items)) : ?>

<?php if ($this->params->get('show_no_items', 1)) : ?>
<p><?php echo JText::_('COM_{{ project.name | constant }}_{{ entity.name | constant }}_NOT_FOUND'); ?></p>
<?php endif; ?>

<?php else : ?>

<form action="<?php echo htmlspecialchars(JFactory::getURI()->toString()); ?>" method="post" name="adminForm" id="adminForm">
	<?php if ($this->params->get('filter_field') != 'hide' || $this->params->get('show_pagination_limit')) :?>
	<fieldset class="filters">
		<?php if ($this->params->get('filter_field') != 'hide') :?>
		<legend class="hidelabeltxt">
			<?php echo JText::_('JGLOBAL_FILTER_LABEL'); ?>
		</legend>

		<div class="filter-search">
			<label class="filter-search-lbl" for="filter-search"><?php echo JText::_('COM_{{ project.name | constant }}_{{ entity.name | constant }}_FIELD_'.$this->params->get('filter_field', '{{ entity.special.title.name }}').'_LABEL').'&#160;'; ?></label>
			<input type="text" name="filter-search" id="filter-search" value="<?php echo $this->escape($this->state->get('list.filter')); ?>" class="inputbox" onchange="document.adminForm.submit();" title="<?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC'); ?>" />
		</div>
		<?php endif; ?>

		<?php if ($this->params->get('show_pagination_limit')) : ?>
		<div class="display-limit">
			<?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?>&#160;
			<?php echo $this->pagination->getLimitBox(); ?>
		</div>
		<?php endif; ?>

		<!-- @TODO add hidden inputs -->
		<input type="hidden" name="filter_order" value="" />
		<input type="hidden" name="filter_order_Dir" value="" />
		<input type="hidden" name="limitstart" value="" />
	</fieldset>
	<?php endif; ?>

	<table class="category">
		<?php if ($this->params->get('show_headings')) :?>
		<thead>
			<tr>
				<th class="list-title" id="tableOrdering">
					<?php  echo JHtml::_('grid.sort', '{{ entity.name | title }}', '{{ entity.name }}.{{ entity.special.title.name }}', $listDirn, $listOrder) ; ?>
				</th>
{% if entity.special.created or entity.special.modified or entity.special.publish_up %}

				<?php if ($date = $this->params->get('list_show_date')) : ?>
				<th class="list-date" id="tableOrdering2">
{% if entity.special.created %}
					<?php if ($date == "created") : ?>
					<?php echo JHtml::_('grid.sort', 'COM_{{ project.name | constant }}_{{ entity.name | constant }}_FIELD_{{ entity.special.created.name | constant }}_LABEL', '{{ entity.name }}.{{ entity.special.created.name }}', $listDirn, $listOrder); ?>
					<?php endif; ?>
{% endif %}
{% if entity.special.modified %}
                    
					<?php if ($date == "modified") : ?>
					<?php echo JHtml::_('grid.sort', 'COM_{{ project.name | constant }}_{{ entity.name | constant }}_FIELD_{{ entity.special.modified.name | constant }}_LABEL', '{{ entity.name }}.{{ entity.special.modified.name }}', $listDirn, $listOrder); ?>
					<?php endif; ?>
{% endif %}
{% if entity.special.publish_up %}

					<?php if ($date == "published") : ?>
					<?php echo JHtml::_('grid.sort', 'COM_{{ project.name | constant }}_{{ entity.name | constant }}_FIELD_{{ entity.special.publish_up | constant }}_LABEL', '{{ entity.name }}.{{ entity.special.publish_up.name }}', $listDirn, $listOrder); ?>
					<?php endif; ?>
{% endif %}

				</th>
				<?php endif; ?>
{% endif %}
{% if entity.special.created_by %}

				<?php if ($this->params->get('list_show_author', 1)) : ?>
				<th class="list-author" id="tableOrdering3">
					<?php echo JHtml::_('grid.sort', 'COM_{{ project.name | constant }}_{{ entity.name | constant }}_FIELD_{{ entity.special.publish_up.name | constant }}_LABEL', 'author', $listDirn, $listOrder); ?>
				</th>
				<?php endif; ?>
{% endif %}
{% if entity.special.hits %}

				<?php if ($this->params->get('list_show_hits', 1)) : ?>
				<th class="list-hits" id="tableOrdering4">
					<?php echo JHtml::_('grid.sort', 'JGLOBAL_HITS', '{{ entity.name }}.hits', $listDirn, $listOrder); ?>
				</th>
				<?php endif; ?>
{% endif %}

			</tr>
		</thead>
		<?php endif; ?>

		<tbody>

			<?php foreach ($this->items as $i => $item) : ?>
			<tr class="<?php echo $this->items[$i]->state == 0 ? 'system-unpublished ' : ''; ?>cat-list-row <?php echo $i % 2; ?>">
{% if entity.special.access %}

				<?php if (in_array($item->access, $this->user->getAuthorisedViewLevels())) : ?>
{% endif %}

				<td class="list-title">
					<a href="<?php echo JRoute::_({{ project.name | class }}HelperRoute::get{{ entity.name | class }}Route($item->{{ entity.special.key.name }}, $item->{{ entity.special.category.name }})); ?>">
						<?php echo $this->escape($item->{{ entity.special.title.name }}); ?>
					</a>

					<?php if ($item->params->get('access-edit')) : ?>
					<span class="actions">
						<span class="edit-icon">
							<?php echo JHtml::_('icon.edit', $item, '{{ entity.name | singular | file }}', $params); ?>
						</span>
					</span>
					<?php endif; ?>
				</td>
{% if entity.special.created or entity.special.modified or entity.special.publish_up %}

				<?php if ($this->params->get('list_show_date')) : ?>
				<td class="list-date">
					<?php echo JHtml::_('date', $item->displayDate, $this->escape($this->params->get('date_format', JText::_('DATE_FORMAT_LC3')))); ?>
				</td>
				<?php endif; ?>
{% endif %}
{% if entity.special.created_by %}

				<?php if ($this->params->get('list_show_author', 1)) : ?>
				<td class="list-author">
					<?php if(!empty($item->author)) : ?>
					<?php if (!empty($item->contactid ) &&  $this->params->get('{{ entity.name | singular | file }}_link_author') == true):?>
					<?php echo JHtml::_('link', JRoute::_('index.php?option=com_contact&view=contact&id='.$item->contactid), $item->author); ?>
					<?php else :?>
					<?php echo $item->author; ?>
					<?php endif; ?>
					<?php endif; ?>
				</td>
				<?php endif; ?>
{% endif %}
{% if entity.special.hits %}

				<?php if ($this->params->get('list_show_hits', 1)) : ?>
				<td class="list-hits">
					<?php echo $item->{{ entity.special.hits.name }}; ?>
				</td>
				<?php endif; ?>
{% endif %}
{% if entity.special.access %}

				<?php else : // Show unauth links. ?>

				<td>
					<?php
					echo $this->escape($item->title).' : ';
					$itemId    = JFactory::getApplication()->getMenu()->getActive()->id;
					$link      = JRoute::_('index.php?option=com_users&view=login&Itemid='.$itemId);
					$returnURL = JRoute::_({{ project.name | class }}HelperRoute::get{{ entity.name | class }}Route($item->slug));
					$fullURL   = new JURI($link);
					$fullURL->setVar('return', base64_encode($returnURL));
					?>
					<a href="<?php echo $fullURL; ?>" class="register">
						<?php echo JText::_( 'COM_CONTENT_REGISTER_TO_READ_MORE' ); ?>
					</a>
				</td>

				<?php endif; ?>
{% endif %}

			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<?php if ($this->category->getParams()->get('access-create')) : ?>
	<span class="actions">
		<span class="edit-icon">
			<?php echo JHtml::_('icon.create', $this->category, '{{ entity.name | singular | file }}', $this->category->params); ?>
		</span>
	</span>
	<?php endif; ?>

	<?php if (!empty($this->items)) : ?>
	<?php if (($this->params->def('show_pagination', 2) == 1  || ($this->params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) : ?>
	<div class="pagination">
		<?php if ($this->params->def('show_pagination_results', 1)) : ?>
		<p class="counter">
			<?php echo $this->pagination->getPagesCounter(); ?>
		</p>
		<?php endif; ?>
		<?php echo $this->pagination->getPagesLinks(); ?>
	</div>
	<?php endif; ?>
	<?php endif; ?>
</form>
<?php  endif; ?>
{% endif %}
