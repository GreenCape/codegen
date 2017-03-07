<?template scope="entity"?>
{% if entity.special.key %}
<?php
/**
 * {{ project.title }} {{ entity.name | title }} Admin List Template
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

/** @var {{ project.name | class }}AdminView{{ entity.name | plural | class }} $this  */

JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_{{ project.name | file }}/assets/css/{{ project.name | file }}.css');

$user      = JFactory::getUser();
{% if entity.special.checked_out %}
$userId    = $user->get('id');
{% endif %}
$listOrder = $this->state->get('list.ordering');
$listDirn  = $this->state->get('list.direction');
{% if entity.special.ordering %}
$canOrder  = $user->authorise('core.edit.state', 'com_{{ project.name | file }}');
$saveOrder = ($listOrder == '{{ entity.storage.table }}.{{ entity.special.ordering.name }}' && $canOrder);
{% endif %}
?>
<form action="<?php echo JRoute::_('index.php?option=com_{{ project.name | file }}&view={{ entity.name | plural | file }}'); ?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JGLOBAL_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>"
				   title="<?php echo JText::_('COM_{{ project.name | constant }}_{{ entity.name | constant }}_SEARCH_IN_TITLE'); ?>" />
			<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		<div class="filter-select fltrt">
{% if entity.special.published %}
			<!--suppress HtmlFormInputWithoutLabel -->
			<select name="filter_state" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED'); ?></option>
				<?php
				echo JHtml::_(
					'select.options',
					JHtml::_('jgrid.publishedOptions'),
					'value',
					'text',
					$this->state->get('filter.state'),
					true
				);
				?>
			</select>
{% endif %}
{% for property in entity.references.foreignKeys  %}
{% set foreignEntity = project.entities[property.type.entity] %}
{% if property.form == 'parameters' %}
{% elseif property.role == 'category' %}
{% elseif property.role == 'created_by' %}
{% elseif property.role == 'modified_by' %}
{% else %}
			<?php
			$formField = new JFormField{{ foreignEntity.name | singular | class }};
			?>
			<!--suppress HtmlFormInputWithoutLabel -->
			<select name="filter_{{ property.name | variable }}" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('COM_{{ project.name | constant }}_{{ entity.name | constant }}_FIELD_{{ property.name | constant }}_SELECT'); ?></option>
				<?php
				echo JHtml::_(
					'select.options',
					$formField->getOptions(),
					'value',
					'text',
					$this->state->get('filter.{{ property.name | variable }}')
				);
				?>
			</select>
{% endif %}
{% endfor %}
{% if entity.special.category %}
			<!--suppress HtmlFormInputWithoutLabel -->
			<select name="filter_category_id" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_CATEGORY'); ?></option>
				<?php
				echo JHtml::_(
					'select.options',
					JHtml::_('category.options', 'com_{{ project.name | file }}'),
					'value',
					'text',
					$this->state->get('filter.category_id')
				);
				?>
			</select>
{% endif %}
{% if entity.special.language %}
			<!--suppress HtmlFormInputWithoutLabel -->
			<select name="filter_language" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_LANGUAGE');?></option>
				<?php
				echo JHtml::_(
					'select.options',
					JHtml::_('contentlanguage.existing', true, true),
					'value',
					'text',
					$this->state->get('filter.language')
				);
				?>
			</select>
{% endif %}
		</div>
	</fieldset>
	<div class="clr"> </div>
	<?php $columnCount = 0; ?>
	<table class="adminlist">
		<thead>
			<tr>
				<?php $columnCount++; ?>
				<th class="center" width="1%"><!-- 1 -->
					<!--suppress HtmlFormInputWithoutLabel -->
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
{% if entity.role != 'map' %}

				<?php $columnCount++; ?>
				<th class="title"><!-- 2 -->
					<?php echo JHtml::_('grid.sort', {% if entity.special.title %}'COM_{{ project.name | constant }}_{{ entity.name | constant }}_FIELD_{{ entity.special.title.name | constant }}_LABEL', '{{ entity.name }}.{{ entity.special.title.name | variable }}'{% else %}'COM_{{ project.name | constant }}_{{ entity.name | constant }}_ITEM', 'name'{% endif %}, $listDirn, $listOrder); ?>
				</th>
{% endif %}
{% for property in entity.listFields %}
{% if property.role == 'key' %}
{% elseif property.role == 'title' %}
{% elseif property.role == 'category' %}
{% else %}

				<?php $columnCount++; ?>
				<th class="center"><!-- 3 -->
					<?php echo JHtml::_('grid.sort', 'COM_{{ project.name | constant }}_{{ entity.name | constant }}_FIELD_{{ property.name | constant }}_LABEL', '{{ entity.name }}.{{ property.name | variable }}', $listDirn, $listOrder); ?>
				</th>
{% endif %}
{% endfor %}
{# override extra_fields_header #}{# endoverride #}
{% for detail in entity.details %}
{% set foreignEntity = project.entities[detail.entity] %}

				<?php $columnCount++; ?>
				<th class="center"><!-- 4 -->
					<?php echo JHtml::_('grid.sort', 'COM_{{ project.name | constant }}_{{ entity.name | constant }}_FIELD_{{ foreignEntity.name | constant }}_COUNT', 'num_{{detail.entity | plural | variable }}_{{detail.reference | variable }}', $listDirn, $listOrder); ?>
				</th>
{% endfor %}
{% if entity.special.featured %}

				<?php $columnCount++; ?>
				<th class="center" width="5%"><!-- 5a -->
					<?php echo JHtml::_('grid.sort', 'COM_{{ project.name | constant }}_{{ entity.name | constant }}_FIELD_{{ property.name | constant }}_LABEL', '{{ entity.name }}.{{ entity.special.featured.name }}', $listDirn, $listOrder); ?>
				</th>
{% endif %}
{% if entity.special.published %}

				<?php $columnCount++; ?>
				<th class="center" width="5%"><!-- 5b -->
					<?php echo JHtml::_('grid.sort', 'JSTATUS', '{{ entity.name }}.{{ entity.special.published.name }}', $listDirn, $listOrder); ?>
				</th>
{% endif %}
{% if entity.special.sticky %}

				<?php $columnCount++; ?>
				<th class="center" width="5%"><!-- 5c -->
					<?php echo JHtml::_('grid.sort', 'COM_{{ project.name | constant }}_{{ entity.name | constant }}_FIELD_{{ property.name | constant }}_LABEL', '{{ entity.name }}.{{ entity.special.sticky.name }}', $listDirn, $listOrder); ?>
				</th>
{% endif %}
{% for property in entity.references.foreignKeys  %}
{% set foreignEntity = project.entities[property.type.entity] %}
{% if property.role == 'category' %}
{% elseif property.form == 'parameters' %}
{% elseif property.role == 'title' %}
{% elseif property.role == 'created_by' %}
{% elseif property.role == 'modified_by' %}
{% else %}

				<?php $columnCount++; ?>
				<th class="title nowrap" width="10%"><!-- 6 -->
					<?php echo JHtml::_('grid.sort', 'COM_{{ project.name | constant }}_{{ entity.name | constant }}_FIELD_{{ property.name | constant }}_LABEL', '{{ property.name | variable }}_name', $listDirn, $listOrder); ?>
				</th>
{% endif %}
{% endfor %}
{% if entity.special.category %}

				<?php $columnCount++; ?>
				<th class="center" width="10%"><!-- 7 -->
					<?php echo JHtml::_('grid.sort', 'JCATEGORY', 'category_title', $listDirn, $listOrder); ?>
				</th>
{% endif %}
{% if entity.special.ordering %}

				<?php $columnCount++; ?>
				<th class="order" width="10%"><!-- 8 -->
					<?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ORDERING', '{{ entity.name }}.{{ entity.special.ordering.name }}', $listDirn, $listOrder); ?>
					<?php if ($saveOrder) : ?>
						<?php echo JHtml::_('grid.order',  $this->items, 'filesave.png', '{{ entity.name }}.saveorder'); ?>
					<?php endif;?>
				</th>
{% endif %}
{% if entity.special.language %}

				<?php $columnCount++; ?>
				<th class="center" width="5%"><!-- 9 -->
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_LANGUAGE', '{{ entity.name }}.{{ entity.special.language.name }}', $listDirn, $listOrder); ?>
				</th>
{% endif %}

				<?php $columnCount++; ?>
				<th class="center" width="1%" class="nowrap"><!-- 10 -->
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', '{{ entity.name }}.{{ entity.special.key.name }}', $listDirn, $listOrder); ?>
				</th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($this->items as $i => $item)
			{
{% if entity.special.ordering %}
				$ordering       = $listOrder == '{{ entity.special.ordering.name }}';
{% endif %}
{% if entity.special.category %}
				$item->cat_link = JRoute::_('index.php?option=com_{{ project.name | file }}&extension=com_{{ project.name | file }}&task=edit&cid[]=' . $item->{{ entity.special.category.name }});
				$canEdit        = $user->authorise('core.edit',       'com_{{ project.name | file }}.category.' . $item->{{ entity.special.category.name }});
{% if entity.special.checked_out %}
				$canCheckin     = $user->authorise('core.manage',     'com_{{ project.name | file }}') || $item->checked_out == $userId || $item->checked_out == 0;
{% endif %}
{% if entity.special.featured or entity.special.published or entity.special.sticky or entity.special.ordering %}
				$canChange      = $user->authorise('core.edit.state', 'com_{{ project.name | file }}.category.' . $item->{{ entity.special.category.name }}) && $canCheckin;
{% endif %}
{% endif %}
{% if not entity.special.category %}
				$canEdit        = $user->authorise('core.edit',       'com_{{ project.name | file }}');
{% if entity.special.checked_out %}
				$canCheckin     = $user->authorise('core.manage',     'com_{{ project.name | file }}') || $item->checked_out == $userId || $item->checked_out == 0;
{% endif %}
{% if entity.special.featured or entity.special.published or entity.special.sticky or entity.special.ordering %}
				$canChange      = $user->authorise('core.edit.state', 'com_{{ project.name | file }}');
{% endif %}
{% endif %}
				?>
				<tr class="row<?php echo $i % 2; ?>">
					<td class="center"><!-- 1 -->
						<?php echo JHtml::_('grid.id', $i, $item->{{ entity.special.key.name }}); ?>
					</td>
{% if entity.role != 'map' %}

					<td class="title nowrap"><!-- 2 -->
{% if entity.special.checked_out %}
						<?php if ($item->{{ entity.special.checked_out.name }}) : ?>
							<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->{{ entity.special.checked_out.name }}, '{{ entity.name | singular | file }}.', $canCheckin); ?>
						<?php endif; ?>
{% endif %}
						<?php if ($canEdit) : ?>
							<a href="<?php echo JRoute::_('index.php?option=com_{{ project.name | file }}&task={{ entity.name | singular | file }}.edit&{{ entity.special.key.name }}=' . (int) $item->{{ entity.special.key.name }}); ?>">
								<?php echo {{ project.name | class }}AdminHelper::truncate($item->title); ?>
							 </a>
						<?php else : ?>
							<?php echo {{ project.name | class }}AdminHelper::truncate($item->title); ?>
						<?php endif; ?>
{% if entity.special.alias %}
						<p class="smallsub">
							<?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias)); ?>
						</p>
{% endif %}
					</td>
{% endif %}
{% for property in entity.listFields %}
{% if property.role == 'key' %}
{% elseif property.role == 'title' %}
{% elseif property.role == 'category' %}
{% else %}

					<td class="center nowrap"><!-- 3 -->
						<?php echo {{ project.name | class }}AdminHelper::truncate($item->{{ property.name | variable }}); ?>
					</td>
{% endif %}
{% endfor %}
{# override extra_fields #}{# endoverride #}
{% for detail in entity.details %}
{% set foreignEntity = project.entities[detail.entity] %}

					<td class="center nowrap"><!-- 4 -->
						<?php
						$link  = 'index.php?option=com_{{ project.name | file }}&view=<<detail.classname:plural:file>>&filter_{{ detail.reference }}=' . $item->{{ property.name | variable }};
						$title = 'COM_{{ project.name | constant }}_{{ entity.name | constant }}_FIELD_{{ foreignEntity.name | constant }}_DESC';
						?>
						<a class="hasTooltip" href="<?php echo JRoute::_($link); ?>" title="<?php echo JText::_($title); ?>">
							<?php echo JText::plural('COM_{{ project.name | constant }}_{{ entity.name | constant }}_FIELD_{{ foreignEntity.name | constant }}_UNIT', $item->num_{{detail.entity | plural | variable }}_{{detail.reference | variable }}); ?>
						</a>
					</td>
{% endfor %}
{% if entity.special.featured %}

					<td class="center"><!-- 5a -->
						<?php if ($canChange) : ?>
							<?php echo $this->toggle($item->{{ entity.special.featured.name }}, $i, '{{ entity.name | singular | file }}.featured_'); ?>
						<?php else : ?>
							<?php
							echo JHtml::_(
								'image',
								'admin/' . ($item->{{ entity.special.featured.name }} ? 'tick.png': 'publish_x.png'),
								$item->{{ entity.special.featured.name }} ? JText::_('COM_{{ project.name | constant }}_ENABLED') : JText::_('COM_{{ project.name | constant }}_DISABLED'),
								null,
								true
							);
							?>
						<?php endif; ?>
					</td>
{% endif %}
{% if entity.special.published %}

					<td class="center"><!-- 5b -->
						<?php if ($canChange) : ?>
							<?php echo $this->toggle($item->{{ entity.special.published.name }}, $i, '{{ entity.name | singular | file }}.', array('publish', 'unpublish')); ?>
						<?php else : ?>
							<?php
							echo JHtml::_(
								'image',
								'admin/' . ($item->{{ entity.special.published.name }} ? 'tick.png': 'publish_x.png'),
								$item->{{ entity.special.published.name }} ? JText::_('COM_{{ project.name | constant }}_ENABLED') : JText::_('COM_{{ project.name | constant }}_DISABLED'),
								null,
								true
							);
							?>
						<?php endif; ?>
					</td>
{% endif %}
{% if entity.special.sticky %}

					<td class="center"><!-- 5c -->
						<?php if ($canChange) : ?>
							<?php echo $this->toggle($item->{{ entity.special.sticky.name }}, $i, '{{ entity.name | singular | file }}.sticky_'); ?>
						<?php else : ?>
							<?php
							echo JHtml::_(
								'image',
								'admin/' . ($item->{{ entity.special.sticky.name }} ? 'tick.png': 'publish_x.png'),
								$item->{{ entity.special.sticky.name }} ? JText::_('COM_{{ project.name | constant }}_ENABLED') : JText::_('COM_{{ project.name | constant }}_DISABLED'),
								null,
								true
							);
							?>
						<?php endif; ?>
					</td>
{% endif %}
{% for property in entity.references.foreignKeys  %}
{% set foreignEntity = project.entities[property.type.entity] %}
{% if property.role == 'category' %}
{% elseif property.form == 'parameters' %}
{% elseif property.role == 'title' %}
{% elseif property.role == 'created_by' %}
{% elseif property.role == 'modified_by' %}
{% elseif foreignEntity.role == 'lookup' %}

					<td class="title nowrap"><!-- 6a -->
						<?php echo {{ project.name | class }}AdminHelper::truncate($item->{{ property.name | variable }}_name); ?>
					</td>
{% else %}

					<td class="title nowrap"><!-- 6b -->
						<a href="<?php echo JRoute::_('index.php?option=com_{{ project.name | file }}&view={{ foreignEntity.name | plural | file }}&filter_search=' . urlencode($item->{{ property.name | variable }}_name)); ?>">
							<?php echo {{ project.name | class }}AdminHelper::truncate($item->{{ property.name | variable }}_name); ?>
						</a>
					</td>
{% endif %}
{% endfor %}
{% if entity.special.category %}

					<td class="title nowrap"><!-- 7 -->
						<?php echo {{ project.name | class }}AdminHelper::truncate($item->category_title); ?>
					</td>
{% endif %}
{% if entity.special.ordering %}

					<td class="order"><!-- 8 -->
						<?php if ($canChange) : ?>
							<?php if ($saveOrder) : ?>
								<?php if ($listDirn == 'asc') : ?>
									<span><?php echo $this->pagination->orderUpIcon($i, {% if entity.special.category %}(@$this->items[$i-1]->catid == $item->catid){% else %}true{% endif %}, '{{ entity.name | singular | file }}.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
									<span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, {% if entity.special.category %}(@$this->items[$i+1]->catid == $item->catid){% else %}true{% endif %}, '{{ entity.name | singular | file }}.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
								<?php elseif ($listDirn == 'desc') : ?>
									<span><?php echo $this->pagination->orderUpIcon($i, {% if entity.special.category %}(@$this->items[$i-1]->catid == $item->catid){% else %}true{% endif %}, '{{ entity.name | singular | file }}.orderdown', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
									<span><?php echo $this->pagination->orderDownIcon($i, {% if entity.special.category %}$this->pagination->total, (@$this->items[$i+1]->catid == $item->catid){% else %}true{% endif %}, '{{ entity.name | singular | file }}.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
								<?php endif; ?>
							<?php endif; ?>
							<?php $disabled = $saveOrder ?  '' : ' disabled="disabled"'; ?>
							<input type="text" name="order[]" size="5" value="<?php echo $item->{{ entity.special.ordering.name }}; ?>"<?php echo $disabled; ?> class="text-area-order" />
						<?php else : ?>
							<?php echo $item->{{ entity.special.ordering.name }}; ?>
						<?php endif; ?>
					</td>
{% endif %}
{% if entity.special.language %}

					<td class="center nowrap"><!-- 9 -->
						<?php if ($item->{{ entity.special.language.name }} == '*'):?>
							<?php echo JText::alt('JALL', 'language'); ?>
						<?php else:?>
							<?php echo $item->language_title ? $this->escape($item->language_title) : JText::_('JUNDEFINED'); ?>
						<?php endif;?>
					</td>
{% endif %}

					<td class="center"><!-- 10 -->
						<?php echo $item->{{ entity.special.key.name }}; ?>
					</td>
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>
	<div><?php echo $this->pagination->getListFooter(); ?></div>

	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>

	<script type="text/javascript">
	var detailRow;
	var detailData;
	var detailElement;
	var detailButton;

	function showDetails(elem) {
		removeDetails();
		detailElement = $(elem);
		detailElement.setStyle('display', 'none');
		var row = detailElement.getParent('tr');
		detailRow  = new Element('tr', {
			class: 'inline-detail-data'
		}).inject(row, 'after');
		detailData = new Element('td', {
			colspan: <?php echo $columnCount; ?>
		}).inject(detailRow, 'top');
		detailButton = new Element('button', {
			type: 'button',
			text: 'Close'
		}).addEvent('click', function(){
			removeDetails();
		}).inject(detailElement, 'after');
		new Request.HTML({
			url: detailElement.href + '&tmpl=component',  /* layout=table */
			method: 'get',
			timeout: 5000,
			update: detailData,
			onRequest: function(){
				new Element('img', {
					src: '../media/system/images/modal/spinner.gif',
					style: 'display: block; margin: 0 auto;'
				}).inject(detailData, 'top');
			},
			onFailure: function(){
				detailData.set('text', 'Sorry, your request failed :(');
			},
			onTimeout: function(){
				detailData.set('text', 'Sorry, your request timed out :(');
			}
		}).send();
		return false;
	}

	function removeDetails() {
		if (detailRow) {
			detailRow.dispose();
		}
		if (detailButton) {
			detailButton.dispose();
		}
		if (detailElement) {
			detailElement.setStyle('display', 'inline');
		}
	}
	</script>
</form>
{% endif %}
